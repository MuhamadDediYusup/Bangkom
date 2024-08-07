<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Course;
use App\Models\LMS\Token;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminTokenController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:lms-list', ['only' => ['index', 'getTokenData']]);
        $this->middleware('permission:lms-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:lms-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:lms-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Token',
            'courses' => Course::select(['slug', 'course_name'])->get(),
        ];

        return view('lms.admin.token.index', $data);
    }

    public function getTokenData($slug = null)
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
            $data = Token::with('course')->whereHas('course', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->get();
        } else {
            $data = Token::with('course')->get();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('lms.admin.token.edit', $row->token_id) . '" class="btn btn-success btn-sm my-md-1 mx-sm-1">Edit</a>';
                $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->token_id . '" data-name="' . $row->token . '">Delete</button>';
                return $btn;
            })
            ->rawColumns(['category_with_badge', 'action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $course = Course::where('slug', $request->course_slug)->first();

        if ($course) {
            $data = [
                'title' => 'Tambah Token',
                'course' => $course,
            ];

            return view('lms.admin.token.create', $data);
        } else {
            return redirect()->back()->with('error', 'Course tidak ditemukan!');
        }

        return redirect()->back()->with('error', 'Silahkan pilih course yang valid!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'course_id' => 'required|unique:lms_access_tokens,course_id,NULL,id,token,' . $request->token,
        ]);

        $token = new Token();
        $token->token = $request->token;
        $token->course_id = $request->course_id;
        $token->save();

        return redirect()->route('lms.admin.token.index')->with('success', 'Token berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $token = Token::find($id)->with('course')->first();
        $course = Course::where('course_id', $token->course_id)->first();

        if ($token) {
            $data = [
                'title' => 'Edit Token',
                'token' => $token,
                'course' => $course
            ];

            return view('lms.admin.token.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Token tidak ditemukan!');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'token' => 'required',
            'course_id' => 'required|unique:lms_access_tokens,course_id,NULL,id,token,' . $request->token,
        ]);

        $token = Token::find($id);
        $token->token = $request->token;
        $token->course_id = $request->course_id;
        $token->save();

        return redirect()->route('lms.admin.token.index')->with('success', 'Token berhasil diubah!');
    }

    public function destroy($id)
    {
        $token = Token::find($id);

        if ($token) {
            $token->delete();
            return redirect()->back()->with('success', 'Token berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Token tidak ditemukan!');
        }
    }
}
