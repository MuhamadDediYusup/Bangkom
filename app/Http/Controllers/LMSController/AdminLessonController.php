<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Course;
use App\Models\LMS\Lesson;
use App\Models\LMS\LessonStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Peopleaps\Scorm\Manager\ScormManager;
use Peopleaps\Scorm\Model\ScormModel;

class AdminLessonController extends Controller
{

    protected $scormManager;

    public function __construct(ScormManager $scormManager)
    {
        $this->middleware('permission:lms-list', ['only' => ['index', 'getLessonData']]);
        $this->middleware('permission:lms-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:lms-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:lms-delete', ['only' => ['destroy']]);

        $this->scormManager = $scormManager;
    }

    public function index()
    {
        $coourse = Course::select(['course_id', 'course_name', 'slug'])
            ->with('modules')
            ->get();
        $data = [
            'title' => 'Manajement Pelajaran',
            'courses' => $coourse
        ];

        return view('lms.admin.lesson.index', $data);
    }

    public function getLessonData($slug = null, $module_id = null)
    {
        // Jika slug dan module_id ada di request, simpan ke session
        if ($slug && $module_id) {
            session(['course_slug' => $slug]);
            session(['module_id' => $module_id]);
        } else {
            // Jika slug tidak ada di request, ambil dari session jika ada
            if (is_null($slug) && session()->has('course_slug')) {
                $slug = session('course_slug');
            }

            // Jika module_id tidak ada di request, ambil dari session jika ada
            if (is_null($module_id) && session()->has('module_id')) {
                $module_id = session('module_id');
            }
        }

        // Fetch the course with modules if the slug is provided
        $course = Course::with('modules')
            ->where('slug', $slug)
            ->first();

        // Initialize the query
        $data = Lesson::select(['lesson_id', 'lesson_chapter', 'lesson_name', 'module_id', 'entry_time', 'content_type'])
            ->with('module')
            ->orderBy('lesson_chapter', 'ASC')
            ->orderBy('module_id', 'DESC');

        // Apply filters based on slug and module_id
        if ($slug && $course) {
            $data->whereHas('module.course', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });

            if ($module_id) {
                $data->where('module_id', $module_id);
            }
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (auth()->user()->hasPermissionTo('lms-edit')) {
                    $btn .= '<a href="' . route('lms.admin.lesson.edit', $row->lesson_id) . '" class="btn btn-success btn-sm my-md-1 mx-sm-1">Edit</a>';
                }
                if (auth()->user()->hasPermissionTo('lms-delete')) {
                    $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->lesson_id . '" data-name="' . $row->lesson_name . '">Delete</button>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function create($course_slug = null, $module_id = null)
    {
        if ($course_slug && $module_id) {
            $course = Course::with(['modules' => function ($query) use ($module_id) {
                $query->where('module_id', $module_id)->limit(1);
            }])
                ->where('slug', $course_slug)
                ->whereHas('modules', function ($query) use ($module_id) {
                    $query->where('module_id', $module_id);
                })
                ->first();

            // last lesson chapter from module ID
            $last_chapter = Lesson::where('module_id', $module_id)->orderBy('lesson_chapter', 'DESC')->first();

            if ($course) {
                $data = [
                    'title' => 'Tambah Pelajaran',
                    'course' => $course,
                    'module' => $course->modules->first(),
                    'last_chapter' => $last_chapter ? $last_chapter->lesson_chapter : 0
                ];

                return view('lms.admin.lesson.create', $data);
            } else {
                return redirect()->route('lms.admin.lesson.index')->with('error', 'Data Kursus atau Module tidak ditemukan');
            }
        } else {
            return redirect()->route('lms.admin.lesson.index')->with('error', 'Silahkan pilih Kursus dan Module terlebih dahulu');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'lesson_name' => 'required',
            'lesson_chapter' => 'required',
            'module_id' => 'required',
            'content_type' => 'required',
            'content_url' => $request->content_type == 'video' ? 'required' : '',
            'content' => 'sometimes|required',
            'content_file' => 'sometimes|required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:102400' // 100 MB
        ]);

        $lesson = new Lesson();

        // Handle file upload
        if ($request->hasFile('content_file')) {
            if ($request->content_type == 'scorm') {
                try {
                    // Use ScormManager to upload and process SCORM package
                    $scorm = $this->scormManager->uploadScormArchive($request->file('content_file'));

                    if ($scorm) {
                        $request->content_url = $scorm->uuid;
                        // $request->content_url = $scorm->uuid . '/' . $scorm->entry_url; // Adjust based on your ScormManager return
                    } else {
                        return back()->withErrors(['content_file' => 'Failed to process SCORM package']);
                    }
                } catch (Exception $e) {
                    return back()->withErrors(['content_file' => 'Failed to upload and unzip SCORM package: ' . $e->getMessage()]);
                }
            } else {
                // Handle other file uploads
                $filename = $this->handleFileUpload($request);
                $request->content_url = $filename;
            }
        }

        // Update content based on type
        if ($request->content_type == 'text' || $request->content_type == 'quiz') {
            $request->content_url = null;
        }

        // Save lesson details
        $lesson->lesson_name = $request->lesson_name;
        $lesson->lesson_chapter = $request->lesson_chapter;
        $lesson->module_id = $request->module_id;
        $lesson->content = $request->content;
        $lesson->content_type = $request->content_type;
        $lesson->content_url = $request->content_url;
        $lesson->save();

        return redirect()->route('lms.admin.lesson.index', [$request->slug, $request->module_id])->with('success', 'Data Pelajaran berhasil ditambahkan');
    }

    public function edit($lesson_id)
    {
        $lesson = Lesson::with('module.course')
            ->with('module', 'module.course')
            ->where('lesson_id', $lesson_id)
            ->first();

        if ($lesson->content_type == 'scorm') {
            $dataScorm = ScormModel::where('uuid', $lesson->content_url)->first();
        }

        if ($lesson) {
            $data = [
                'title' => 'Edit Pelajaran',
                'lesson' => $lesson,
                'module' => $lesson->module,
                'course' => $lesson->module->course,
                'dataScorm' => $dataScorm ?? null
            ];

            return view('lms.admin.lesson.edit', $data);
        } else {
            return redirect()->route('lms.admin.lesson.index')->with('error', 'Data Pelajaran tidak ditemukan');
        }
    }

    public function update(Request $request, $lesson_id)
    {
        $request->validate([
            'lesson_name' => 'required',
            'lesson_chapter' => 'required',
            'module_id' => 'required',
            'content_type' => 'required',
            'content_url' => $request->content_type == 'pdf' || $request->content_type == 'video' || $request->content_type == 'scorm' ? 'required' : '',
            'content' => 'sometimes|required',
            'content_file' => 'sometimes|required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:10240' // 10 MB
        ]);

        DB::beginTransaction();
        try {
            // Get lesson data
            $lesson = Lesson::where('lesson_id', $lesson_id)->firstOrFail();

            // Handle file upload
            if ($request->hasFile('content_file')) {
                // Delete old file if exists
                $this->deleteOldFile($lesson);

                if ($request->content_type == 'scorm') {
                    try {
                        // Use ScormManager to upload and process SCORM package
                        $scorm = $this->scormManager->uploadScormArchive($request->file('content_file'));

                        if ($scorm) {
                            $request->content_url = $scorm->uuid;
                            // $request->content_url = $scorm->uuid . '/' . $scorm->entry_url; // Adjust based on your ScormManager return
                        } else {
                            return back()->withErrors(['content_file' => 'Failed to process SCORM package']);
                        }
                    } catch (Exception $e) {
                        return back()->withErrors(['content_file' => 'Failed to upload and unzip SCORM package: ' . $e->getMessage()]);
                    }
                } else {
                    // Handle other file uploads
                    $filename = $this->handleFileUpload($request);
                    $request->content_url = $filename;
                }
            }

            // Set content_url and content based on content_type
            if ($request->content_type == 'text' || $request->content_type == 'quiz') {
                $request->content_url = null; // Ensure content_url is null for text and quiz types
            } else if ($request->content_type == 'pdf' || $request->content_type == 'video') {
                $request->content_url = $request->content_url; // Use content_url for pdf and video types
            }

            // Update lesson data
            $lesson->lesson_name = $request->lesson_name;
            $lesson->lesson_chapter = $request->lesson_chapter;
            $lesson->module_id = $request->module_id;
            $lesson->content = $request->content;
            $lesson->content_type = $request->content_type;
            $lesson->content_url = $request->content_url;
            $lesson->save();

            DB::commit();
            return redirect()->route('lms.admin.lesson.index', [$request->slug, $request->module_id])->with('success', 'Data Pelajaran berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengupdate data pelajaran: ' . $e->getMessage()]);
        }
    }

    public function destroy($lesson_id)
    {
        $lesson = Lesson::where('lesson_id', $lesson_id)->first();

        if ($lesson) {
            // Delete file if exists
            $this->deleteOldFile($lesson);

            // delete lesson_status
            $lesson_status = LessonStatus::where('lesson_id', $lesson_id)->get();
            foreach ($lesson_status as $key => $value) {
                $value->delete();
            }

            $lesson->delete();

            return redirect()->back()->with('success', 'Data Pelajaran berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data Pelajaran tidak ditemukan');
        }
    }

    private function handleFileUpload($request)
    {
        $file = $request->file('content_file');
        $filename =  $request->module_id . "_" . date('mY') . $request->lesson_chapter . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('files/lessons/' . $request->module_id), $filename);
        return $request->module_id . '/' . $filename;
    }

    private function deleteOldFile($lesson)
    {
        if ($lesson->content_url) {
            $oldFilePath = public_path('files/lessons/' . $lesson->content_url);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
    }
}
