<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:lms-list', ['only' => ['index', 'getCategoryData']]);
        $this->middleware('permission:lms-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:lms-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:lms-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Kategori',
        ];
        return view('lms.admin.category.index', $data);
    }

    public function getCategoryData(Request $request)
    {
        $data = Category::select(['category_id', 'category_name', 'slug', 'color_tag'])
            ->orderBy('entry_time', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('category_with_badge', function ($row) {
                return '<span class="text-white badge ' . $row->color_tag . '">' . $row->category_name . '</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (auth()->user()->hasPermissionTo('lms-edit')) {
                    $btn .= '<a href="' . route('lms.admin.category.edit', $row->category_id) . '" class="btn btn-success btn-sm my-md-1 mx-sm-1">Edit</a>';
                }
                if (auth()->user()->hasPermissionTo('lms-delete')) {
                    $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->category_id . '" data-name="' . $row->category_name . '">Delete</button>';
                }
                return $btn;
            })
            ->rawColumns(['category_with_badge', 'action'])
            ->make(true);
    }


    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
        ];
        return view('lms.admin.category.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
            'slug' => 'required',
            'color_tag' => 'required',
        ]);


        $category = new Category();
        $category->category_name = $request->category_name;
        $category->slug = $request->slug;
        $category->color_tag = $request->color_tag;
        $category->save();

        return redirect()->route('lms.admin.category.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $data = [
            'title' => 'Edit Kategori',
            'category' => $category,
        ];
        return view('lms.admin.category.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required',
            'slug' => 'required',
            'color_tag' => 'required',
        ]);

        $category = Category::findOrFail($id);
        $category->category_name = $request->category_name;
        $category->slug = $request->slug;
        $category->color_tag = $request->color_tag;
        $category->save();

        return redirect()->route('lms.admin.category.index')->with('success', 'Kategori berhasil diubah');
    }

    public function destroy($id)
    {

        try {
            $category = Category::find($id);
            $category->delete();

            return redirect()->route('lms.admin.category.index')->with('success', 'Kategori berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->route('lms.admin.category.index')->with('error', 'Kategori gagal dihapus, pastikan tidak ada kursus yang terkait');
        }
    }
}
