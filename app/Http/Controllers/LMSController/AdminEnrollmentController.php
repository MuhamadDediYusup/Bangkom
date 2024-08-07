<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Course;
use App\Models\LMS\Enrollment;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Pegawai;
use Yajra\DataTables\Facades\DataTables;

class AdminEnrollmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:lms-list', ['only' => ['index', 'getEnrollmentData', 'getEmployeeData']]);
        $this->middleware('permission:lms-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:lms-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:lms-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Enrollment',
            'courses' => Course::select(['slug', 'course_name'])->get(),
        ];

        return view('lms.admin.enrollment.index', $data);
    }

    public function getEnrollmentData($slug = null)
    {
        // Cek apakah slug ada di request
        if ($slug) {
            // Simpan slug ke session dengan nama course_slug
            session(['course_slug' => $slug]);
            session()->forget('module_id');
        } else {
            // Ambil slug dari session jika tidak ada di request
            $slug = session('course_slug');
        }

        if ($slug) {
            $data = Enrollment::with('employee', 'course')->whereHas('course', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->get();
        } else {
            $data = Enrollment::with('employee', 'course')->get();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (auth()->user()->hasPermissionTo('lms-delete')) {
                    $btn = '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->enrollment_id . '" data-name="' . $row->user_id . '">Delete</button>';
                }
                return $btn;
            })
            ->rawColumns(['category_with_badge', 'action'])
            ->make(true);
    }

    public function getEmployeeData()
    {
        $data = PegawaiModel::select(['nip', 'nama_lengkap'])->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (auth()->user()->hasPermissionTo('lms-create')) {
                    $btn .= '<button type="button" class="add btn btn-primary btn-sm" data-id="' . $row->nip . '" data-name="' . $row->nama_lengkap . '">Tambah</button>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(Request $request)
    {

        $course = Course::where('slug', $request->course_slug)->first();

        if (!$course) {
            return redirect()->back()->with('error', 'Pilih kursus terlebih dahulu!');
        }

        $data = [
            'title' => 'Tambah Enrollment',
            'course' => $course
        ];

        return view('lms.admin.enrollment.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:lms_courses,course_id',
            'employees' => 'required|array',
            'employees.*' => 'exists:pegawai,nip',
        ]);

        $course_id = $request->course_id;
        $slug = $request->slug;
        $employees = $request->employees;

        foreach ($employees as $nip) {
            Enrollment::create([
                'course_id' => $course_id,
                'user_id' => $nip,
                'enrolled_at' => now(),
            ]);
        }

        return redirect()->route('lms.admin.enrollment.index', [$slug])->with('success', 'Enrollment berhasil disimpan!');
    }

    public function destroy($id)
    {
        $enrollment = Enrollment::find($id);

        if ($enrollment) {
            $enrollment->delete();
            return redirect()->back()->with('success', 'Enrollment berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Enrollment tidak ditemukan!');
    }
}
