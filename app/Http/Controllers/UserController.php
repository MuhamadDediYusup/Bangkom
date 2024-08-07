<?php

namespace App\Http\Controllers;

use Pegawai;
use App\Models\User;
use Illuminate\Support\Arr;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => 'Master User',
            'users' => DB::table('users')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->join('pegawai', 'users.user_id', '=', 'pegawai.nip', 'left outer')
                ->select('users.*', 'roles.name as role_name', 'pegawai.nama_lengkap', 'pegawai.perangkat_daerah', 'pegawai.id_perangkat_daerah as id_perangkat_daerah_master', 'pegawai.jabatan')
                ->orderBy('role_name', 'asc')
                ->orderBy('id_perangkat_daerah_master', 'asc')
                ->get(),
        ];

        // dd($data['users']);

        return view('user.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Master User',
            'role' => Role::get(),
        ];
        return view('user.user.create', $data);
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
            'user_id' => 'required|unique:users,user_id',
            'user_name' => 'required',
            'password' => 'required|same:confirm-password|min:8',
            'confirm-password' => 'required|same:password|min:8',
            'roles' => 'required_unless:role,null',
        ]);

        if ($request->roles[0] == null) {
            return redirect()
                ->back()
                ->with('error', 'Pilih Role User');
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = new User;
        $input['id_perangkat_daerah'] = $request->id_perangkat_daerah;
        $input['entry_user'] = Auth::user()->user_id;
        $input['entry_time'] = date('Y-m-d H:i:s');
        $input['edit_user'] = null;
        $input['edit_time'] = null;
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil ditambahkans');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        $profile = DB::table('pegawai')
            ->where('nip', $user->user_id)
            ->get()
            ->first();

        $data = [
            'title' => 'Master User',
            'role' => Role::get(),
            'data' => $user,
            'profile' => $profile,
        ];

        return view('user.user.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $dataPegawai = PegawaiModel::where('nip', $user->user_id)->get()->first();

        if (empty($dataPegawai)) {
            return redirect()
                ->back()
                ->with('error', 'Maaf... Data Pegawai Tidak Ditemukan !');
        }

        $data = [
            'title' => 'Edit Master User',
            'role' => Role::get(),
            'user' => $user,
            'pegawai' => $dataPegawai,
        ];
        return view('user.user.edit', $data);
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

        $input = $request->all();
        if (!empty($input['password'])) {
            $this->validate($request, [
                'password' => 'same:confirm-password|min:8',
                'confirm-password' => 'same:password|min:8',
            ]);
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);
        }
        $user = User::find($id);
        $user->id_perangkat_daerah = $request->id_perangkat_daerah;
        $user->edit_user = Auth::user()->user_id;
        $user->user_id = $request->user_id;
        $user->user_name = $request->user_name;
        // $user->edit_time = date('Y-m-d H:i:s');
        $user->update($input);

        DB::table('model_has_roles')
            ->where('model_id', $id)
            ->delete();

        $user->assignRole($request->input('roles'));

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        DB::table('model_has_roles')
            ->where('model_id', $id)
            ->delete();
        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function detailUser()
    {
        $profile = DB::table('pegawai')
            ->where('nip', Auth::user()->user_id)
            ->get()
            ->first();

        if (empty($profile)) {
            return redirect('/')
                ->with('error', 'Maaf... Data Pegawai Tidak Ditemukan !');
        }

        $data = [
            'title' => 'Profile User',
            'data' => $profile,
        ];

        return view('user.detail_user.index', $data);
    }

    public function updatePassword(Request $request)
    {
        $rule = [
            'password' => 'required|min:8',
            'password_confirm' => 'required|same:password|min:8',
        ];

        $this->validate(request(), $rule);
        $input = request()->all();
        $input['password'] = Hash::make($input['password']);
        $input['edit_user'] = Auth::user()->user_id;
        $user = User::find(Auth::user()->id);
        // $user->edit_user = Auth::user()->user_id;
        // $user->edit_time = date('Y-m-d H:i:s');
        $user->update($input);

        $user = Auth::user();
        $id = $user->id;
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Update last logout
        $dbUser = new \App\Models\User();
        $time = date('Y-m-d H:i:s');
        $dbUser = $dbUser->find($id);
        $dbUser->logout_time = $time;
        $dbUser->save();

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil diubah, silahkan Login kembali');
    }

    public function activities()
    {

        $combinedQuery = DB::table('laporan')
            ->select(
                DB::raw('entry_user as user'),
                DB::raw('YEAR(entry_time) as year'),
                DB::raw('MONTH(entry_time) as month'),
                DB::raw('count(*) as total'),
                DB::raw("'create' as status")
            )
            ->where('entry_user', 'like', '%' . Auth::user()->user_id . '%')
            ->whereRaw('entry_time >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)')
            ->groupBy('year', 'month')
            ->unionAll(
                DB::table('laporan')
                    ->select(
                        DB::raw('edit_user as user'),
                        DB::raw('YEAR(edit_time) as year'),
                        DB::raw('MONTH(edit_time) as month'),
                        DB::raw('count(*) as total'),
                        DB::raw("'edit' as status")
                    )
                    ->where('edit_user', 'like', '%' . Auth::user()->user_id . '%')
                    ->whereRaw('edit_time >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)')
                    ->groupBy('year', 'month')
            )
            ->get();

        // Initialize an empty result array
        $resultArray = [];

        // Loop through each month
        foreach ($combinedQuery as $combinated) {
            $year = $combinated->year;
            $month = str_pad($combinated->month, 2, '0', STR_PAD_LEFT);
            $key = $year . '-' . $month;

            // Check if the key already exists in the result array
            if (!isset($resultArray[$key])) {
                $resultArray[$key] = [
                    'year' => $year,
                    'month' => $month,
                    'create' => 0,
                    'edit' => 0,
                ];
            }

            // Update the total count based on the status
            $resultArray[$key][$combinated->status] += $combinated->total;
        }

        // descending sort by year and month
        krsort($resultArray);

        // month
        $month = [
            '00' => '-',
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'Nopember',
            '12' => 'Desember',
        ];

        // make result array month and year to string
        foreach ($resultArray as $key => $val) {
            $resultArray[$key]['month'] = $month[$val['month']];
            $resultArray[$key]['year'] = $val['year'];
        }

        // total create and edit
        $totalCreate = 0;
        $totalEdit = 0;
        foreach ($resultArray as $key => $val) {
            $totalCreate += $val['create'];
            $totalEdit += $val['edit'];
        }

        $data = [
            'title' => 'Aktivitas User',
            'data' => $resultArray,
            'totalCreate' => $totalCreate,
            'totalEdit' => $totalEdit,
        ];

        return view('activities.index', $data);
    }
}
