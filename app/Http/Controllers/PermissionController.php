<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => 'Master Permission',
            'permission' => Permission::orderBy('name', 'ASC')->get()
        ];
        return view('user.permission.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Permission'
        ];
        return view('user.permission.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'permission' => 'required',
        ]);

        Permission::create(['name' => $request->input('permission')]);

        return redirect()->route('permission.index')
            ->with('success', 'Permission berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [
            'title' => 'Permission',
            'data' => Permission::find($id)
        ];

        return view('user.permission.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'permission' => 'required|unique:permissions,name',
        ]);

        $input = $request->all();
        $permission = Permission::find($id);
        $permission->name = $request->permission;
        $permission->save();

        return redirect()->route('permission.index')
            ->with('success', 'Permission berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        // dd($role->permissions->pluck('name'));
        foreach (Role::get() as $role) {
            foreach ($role->permissions->pluck('name') as $name) {
                if ($name == $permission->name) {
                    return redirect()->route('permission.index')
                        ->with('error', 'Permission tidak dapat dihapus karena masih digunakan oleh role ' . $role->name . '');
                }
            }
        }
        $permission->delete();
        return redirect()->route('permission.index')
            ->with('success', 'Permission berhasil dihapus');
    }
}
