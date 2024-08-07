<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Course;
use App\Models\LMS\Lesson;
use App\Models\LMS\Module;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminModuleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:lms-list', ['only' => ['index', 'getModuleData']]);
        $this->middleware('permission:lms-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:lms-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:lms-delete', ['only' => ['destroy']]);
    }

    function index()
    {
        $data = [
            'title' => 'Module Management',
            'courses' => Course::select(['slug', 'course_name'])->get(),
        ];

        return view('lms.admin.modules.index', $data);
    }

    function getModuleData(Request $request)
    {

        // Ambil slug dari request
        $slug = $request->slug;

        // Cek apakah slug ada di request
        if ($slug) {
            // Simpan slug ke session dengan nama course_slug
            session(['course_slug' => $slug]);
            session()->forget('module_id');
        } else {
            // Ambil slug dari session jika tidak ada di request
            $slug = session('course_slug');
        }

        $course_id = Course::select('course_id')->where('slug', $slug)->first();

        // Get Module data from course ID or all module data
        if ($slug && $course_id) {
            $data = Module::select(['module_id', 'module_name', 'course_id', 'entry_time', 'module_chapter'])
                ->with('course:course_id,slug,course_name')
                ->where('course_id', $course_id->course_id)
                ->orderBy('module_chapter', 'ASC')
                ->orderBy('course_id', 'DESC');
        } else {
            $data = Module::select(['module_id', 'module_name', 'course_id', 'entry_time', 'module_chapter'])
                ->with('course:course_id,slug,course_name') // Memastikan hanya field yang diperlukan yang diambil dari relasi course
                ->orderBy('module_chapter', 'ASC')
                ->orderBy('course_id', 'DESC');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (auth()->user()->hasPermissionTo('lms-list')) {
                    $btn .= '<a href="' . route('lms.admin.lesson.index', [$row->course->slug, $row->module_id]) . '" class="btn btn-primary btn-sm my-md-1 mx-sm-1">Pelajaran</a>';
                }

                if (auth()->user()->hasPermissionTo('lms-edit')) {
                    $btn .= '<a href="' . route('lms.admin.module.edit', $row->module_id) . '" class="btn btn-success btn-sm my-md-1 mx-sm-1">Edit</a>';
                }

                if (auth()->user()->hasPermissionTo('lms-delete')) {
                    $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->module_id . '" data-name="' . $row->module_name . '">Delete</button>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getModuleByCourseId(Request $request)
    {

        $slug = $request->input('slug');
        $course = Course::where('slug', $slug)->first();

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $modules = Module::where('course_id', $course->course_id)->get();
        return response()->json($modules);
    }

    public function getModuleByCourseIdQuiz(Request $request)
    {
        $slug = $request->input('slug');
        $course = Course::where('slug', $slug)->first();

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $modules = Module::where('course_id', $course->course_id)
            ->when($request->moduleId, function ($query) use ($request) {
                $query->where('module_id', $request->moduleId);
            })
            ->whereHas('lessons', function ($query) {
                $query->where('content_type', 'quiz');
            })
            ->get();

        return response()->json($modules);
    }

    public function create($slug = null)
    {
        if ($slug) {
            $course = Course::select(['course_id', 'course_name', 'slug'])->where('slug', $slug)->first();


            if ($course) {
                // check chapter number terakhir
                $last_chapter = Module::select('module_chapter')->where('course_id', $course->course_id)->orderBy('module_chapter', 'DESC')->first();
                $last_chapter = $last_chapter ? $last_chapter->module_chapter : 0;

                $data = [
                    'title' => 'Create Module',
                    'course' => $course,
                    'last_chapter' => $last_chapter,
                ];
                return view('lms.admin.modules.create', $data);
            }
        }

        return redirect()->route('lms.admin.module.index')->with('error', 'Kursus tidak ditemukan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required',
            'course_id' => 'required',
            'module_name' => 'required',
            'module_chapter' => 'required',
            'estimated_time' => 'required',
        ]);

        $module = new Module();
        $module->course_id = $request->course_id;
        $module->module_chapter = $request->module_chapter;
        $module->module_name = $request->module_name;
        $module->description = $request->description;
        $module->estimated_time = $request->estimated_time;
        $module->save();

        return redirect()->route('lms.admin.module.index', [$request->slug])->with('success', 'Modul berhasil ditambahkan');
    }

    public function edit($id)
    {
        $module = Module::where('module_id', $id)->first();
        $course = Course::select(['course_id', 'course_name', 'slug'])->where('course_id', $module->course_id)->first();

        // check chapter number terakhir
        $last_chapter = Module::select('module_chapter')->where('course_id', $course->course_id)->orderBy('module_chapter', 'DESC')->first();
        $last_chapter = $last_chapter ? $last_chapter->module_chapter : 0;

        if ($module) {
            $data = [
                'title' => 'Edit Module',
                'course' => $course,
                'module' => $module,
                'last_chapter' => $last_chapter,
            ];
            return view('lms.admin.modules.edit', $data);
        }

        return redirect()->route('lms.admin.module.index')->with('error', 'Modul tidak ditemukan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'module_name' => 'required',
            'module_chapter' => 'required',
            'estimated_time' => 'required',
        ]);

        $module = Module::find($id);
        $module->module_chapter = $request->module_chapter;
        $module->module_name = $request->module_name;
        $module->description = $request->description;
        $module->estimated_time = $request->estimated_time;
        $module->save();

        return redirect()->route('lms.admin.module.index', [$module->course->slug])->with('success', 'Modul berhasil diubah');
    }

    public function destroy($id)
    {

        // delete lesson first
        $lessons = Lesson::where('module_id', $id)->get();
        foreach ($lessons as $lesson) {
            // jika content_type 'pdf','quiz' maka hapus file
            if ($lesson->content_type == 'pdf' || $lesson->content_type == 'quiz') {
                $file_path = public_path('storage/' . $lesson->content);
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // delete quiz and quiz question if exist
            if ($lesson->quiz != null) {
                $quiz = $lesson->quiz;
                $quiz->quiz_questions()->delete();
                $quiz->delete();
            }

            $lesson->delete();
        }

        $module = Module::find($id);
        $module->delete();

        return redirect()->route('lms.admin.module.index', [$module->course->slug])->with('success', 'Modul berhasil dihapus');
    }
}
