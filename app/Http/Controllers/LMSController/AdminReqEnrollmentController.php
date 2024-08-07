<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Course;
use App\Models\LMS\Enrollment;
use App\Models\LMS\RequestAccess;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminReqEnrollmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:lms-list', ['only' => ['index', 'getReqEnrollmentData']]);
        $this->middleware('permission:lms-create', ['only' => ['approve', 'reject']]);
        $this->middleware('permission:lms-edit', ['only' => ['approve', 'reject']]);
        $this->middleware('permission:lms-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Permintaan Enrollment',
            'courses' => Course::get(),
        ];

        return view('lms.admin.req_enrollment.index', $data);
    }

    public function getReqEnrollmentData($slug = null)
    {

        if ($slug) {
            // Simpan slug ke session dengan nama course_slug
            session(['course_slug' => $slug]);
            session()->forget('module_id');
        } else {
            // Ambil slug dari session jika tidak ada di request
            $slug = session('course_slug');
        }

        if ($slug) {
            $data = RequestAccess::with('course', 'employee')->whereHas('course', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->orderBy('status', 'asc')->get();
        } else {
            $data = RequestAccess::with('course', 'employee')->orderBy('status', 'asc')->get();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (auth()->user()->hasPermissionTo('lms-delete')) {
                    $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->request_id . '" data-name="' . $row->user_id . '">Delete</button>';
                }
                return $btn;
            })
            ->addColumn('approval_action', function ($row) {
                $btn = '';
                if (auth()->user()->hasPermissionTo('lms-edit') || auth()->user()->hasPermissionTo('lms-create')) {
                    if ($row->status == '0') {
                        $btn .= '<button type="button" class="approve btn btn-success btn-sm" data-id="' . $row->request_id . '" data-name="' . $row->user_id . '">Setujui</button>';
                        $btn .= '<button type="button" class="reject btn btn-danger btn-sm  mx-sm-1" data-id="' . $row->request_id . '" data-name="' . $row->user_id . '">Tolak</button>';
                    } elseif ($row->status == '1') {
                        $btn = '<button type="button" class="approve btn btn-success btn-sm" data-id="' . $row->request_id . '" data-name="' . $row->user_id . '" disabled>Setujui</button>';
                        $btn .= '<button type="button" class="reject btn btn-danger btn-sm  mx-sm-1" data-id="' . $row->request_id . '" data-name="' . $row->user_id . '">Tolak</button>';
                    } elseif ($row->status == '2') {
                        $btn = '<button type="button" class="approve btn btn-success btn-sm" data-id="' . $row->request_id . '" data-name="' . $row->user_id . '">Setujui</button>';
                        $btn .= '<button type="button" class="reject btn btn-danger btn-sm  mx-sm-1" data-id="' . $row->request_id . '" data-name="' . $row->user_id . '" disabled>Tolak</button>';
                    }
                }
                // $btn = '<button type="button" class="approve btn btn-success btn-sm" data-id="' . $row->request_id . '" data-name="' . $row->user_id . '">Setujui</button>';
                // $btn .= '<button type="button" class="reject btn btn-danger btn-sm  mx-sm-1" data-id="' . $row->request_id . '" data-name="' . $row->user_id . '">Tolak</button>';
                return $btn;
            })
            ->rawColumns(['approval_action', 'action'])
            ->make(true);
    }

    public function approve($id)
    {
        $requestAccess = RequestAccess::findOrfail($id);

        $requestAccess->status = '1';
        $requestAccess->save();

        $enrollment = new Enrollment();
        $enrollment->user_id = $requestAccess->user_id;
        $enrollment->course_id = $requestAccess->course_id;
        $enrollment->entry_user = auth()->user()->id;
        $enrollment->edit_user = auth()->user()->id;
        $enrollment->save();

        return redirect()->back()->with('success', 'Permintaan berhasil disetujui');
    }

    public function reject($id)
    {
        $requestAccess = RequestAccess::findOrfail($id);
        $requestAccess->status = '2';
        $requestAccess->save();

        $enrollment = Enrollment::where('user_id', $requestAccess->user_id)->where('course_id', $requestAccess->course_id)->delete();

        return redirect()->back()->with('success', 'Permintaan berhasil ditolak');
    }

    public function destroy($id)
    {
        $requestAccess = RequestAccess::findOrfail($id);
        $requestAccess->delete();

        return redirect()->back()->with('success', 'Permintaan berhasil dihapus');
    }
}
