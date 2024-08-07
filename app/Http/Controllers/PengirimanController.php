<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pengiriman;
use App\Models\UsulanModel;
use App\Models\LaporanModel;
use Illuminate\Http\Request;
use App\Exports\UsulanExport;
use App\Exports\PengirimanExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('id_perangkat_daerah')) {
            if (session('id_perangkat_daerah') != $request->id_perangkat_daerah) {
                session()->forget('id_perangkat_daerah');
                session(['id_perangkat_daerah' => $request->id_perangkat_daerah]);
            } else if ($request->id_perangkat_daerah == '' || $request->id_perangkat_daerah == 'all') {
                session(['id_perangkat_daerah' => 'all']);
            } else {
                session(['id_perangkat_daerah' => $request->id_perangkat_daerah]);
            }
        } else {
            if (session('id_perangkat_daerah') == null) {
                session()->forget('id_perangkat_daerah');
            }
        }

        if ($request->has('filter_tanggal')) {
            if (session('filter_tanggal') != $request->filter_tanggal) {
                session()->forget('filter_tanggal');
                session(['filter_tanggal' => $request->filter_tanggal]);
            } else if ($request->filter_tanggal == '') {
                session()->forget('filter_tanggal');
            } else {
                session(['filter_tanggal' => $request->filter_tanggal]);
            }
        } else {
            if (session('filter_tanggal') == null) {
                session()->forget('filter_tanggal');
            }
        }

        if ($request->has('filter_nama_diklat')) {
            if (session('filter_nama_diklat') != $request->filter_nama_diklat) {
                session()->forget('filter_nama_diklat');
                session(['filter_nama_diklat' => $request->filter_nama_diklat]);
            } else if ($request->filter_nama_diklat == '') {
                session()->forget('filter_nama_diklat');
            } else {
                session(['filter_nama_diklat' => $request->filter_nama_diklat]);
            }
        } else {
            if (session('filter_nama_diklat') == null) {
                session()->forget('filter_nama_diklat');
            }
        }

        if ($request->has('filter_nama_nip')) {
            if (session('filter_nama_nip') != $request->filter_nama_nip) {
                session()->forget('filter_nama_nip');
                session(['filter_nama_nip' => $request->filter_nama_nip]);
            } else if ($request->filter_nama_nip == '') {
                session()->forget('filter_nama_nip');
            } else {
                session(['filter_nama_nip' => $request->filter_nama_nip]);
            }
        } else {
            if (session('filter_nama_nip') == null) {
                session()->forget('filter_nama_nip');
            }
        }

        try {
            $data = [
                'title' => 'Daftar Pengiriman Pengembangan Kompetensi',
            ];

            return view('pengiriman.index', $data);
        } catch (\Throwable $th) {
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan');
        }
    }

    public function form_pengiriman(Request $request)
    {
        try {
            $data = [
                'title' => 'Form Pengiriman Pengembangan Kompetensi',
                'perangkat_daerah' => getData('master_perangkat_daerah', 1),
            ];

            if ($request->has('id_perangkat_daerah')) {

                if (auth()->user()->can('search-option-disabled')) {
                    $request->id_perangkat_daerah = checkIdPerangkatDaerah();
                } else {
                    $request->id_perangkat_daerah = $request->id_perangkat_daerah;
                }

                $data['pengiriman'] = getData('list_peg', $request->id_perangkat_daerah . '+' . $request->nama);
                if ($data['pengiriman']->Status != 'Success|True') {
                    redirect()->route('form_pengiriman')->with('error', 'Data pegawai tidak ditemukan');
                }
            }
            return view('pengiriman.form_pengiriman', $data);
        } catch (\Throwable $th) {
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($nip, $id)
    {
        $data = [
            'title' => 'Pengiriman Pengembangan Kompetensi',
            'usulan' => DB::table('usulan')
                ->join('pegawai', 'pegawai.nip', '=', 'usulan.nip')
                ->select('usulan.*', 'pegawai.nama_lengkap')
                ->where('usulan.id_usul', $id)
                ->where('usulan.nip', $nip)
                ->first(),
            'status' => '0',
        ];
        return view('pengiriman.create', $data);
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
            'tgl_surat' => 'required',
            'nomor_surat' => 'required',
            'file_spt' => 'mimes:pdf|max:2048',

        ]);

        try {

            // XSS Filtering & Sanitizing Input Data
            $request->merge(array_map('strip_tags', $request->all()));
            $request->merge(array_map('trim', $request->all()));

            $nip = $request->nip;
            $input = $request->all();
            $input['status'] = '0';

            $uniq_id = Carbon::now()->timestamp;

            if ($request->hasFile('file_spt')) {
                $spt_ext = $request->file('file_spt')->getClientOriginalExtension();
                $file = $request->file('file_spt');
                $fileName = $nip . '_SPT_' . $request->tgl_surat . '_' . $uniq_id . '.' . $spt_ext;
                $destinationPath = public_path() . '/Lamp_SPT';
                $file->move($destinationPath, $fileName);
                $input['file_spt'] = $fileName;
            }

            Pengiriman::create($input);

            return redirect()->route('pengiriman.index')
                ->with('success', 'Berhasil menambahkan pengiriman');
        } catch (\Throwable $th) {
            return redirect()->route('pengiriman.index')
                ->with('error', 'Pengiriman gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengiriman  $pengiriman
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengiriman  $pengiriman
     * @return \Illuminate\Http\Response
     */
    public function edit($nip, $id)
    {
        $pengiriman = DB::table('pengiriman')
            ->join('usulan', 'pengiriman.id_usul', '=', 'usulan.id_usul')
            ->join('pegawai', 'pengiriman.nip', '=', 'pegawai.nip')
            ->select('pengiriman.*', 'usulan.jenis_diklat', 'usulan.sub_jenis_diklat', 'usulan.rumpun_diklat', 'usulan.nama_diklat', 'pegawai.nama_lengkap', 'usulan.id_diklat', 'usulan.id_siasn', 'usulan.sertifikat_siasn')
            ->where('pengiriman.id_pengiriman', $id)
            ->where('pengiriman.nip', $nip)
            ->get();

        $data = [
            'title' => 'Perubahan Pengiriman Pengembangan Kompetensi',
            'pengiriman' => $pengiriman,
            'master_diklat' => getData('master_diklat', 1),
        ];

        return view('pengiriman.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengiriman  $pengiriman
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'file_spt' => 'mimes:pdf|max:2048',
        ]);

        try {
            try {
                $request->merge(array_map('strip_tags', $request->all()));
                $request->merge(array_map('trim', $request->all()));
            } catch (\Throwable $th) {
                return redirect()
                    ->route('pengiriman')
                    ->with('error', 'Terjadi kesalahan');
            }

            $input = $request->all();
            $uniq_id = Carbon::now()->timestamp;
            $nip = $request->nip;
            $pengiriman = Pengiriman::find($id);

            // save to usulan
            $usulan = UsulanModel::find($request->id_usul);
            $usulan->jenis_diklat = explode(",", $request->nama_bangkom)[0];
            $usulan->sub_jenis_diklat = explode(",", $request->nama_bangkom)[1];
            $usulan->rumpun_diklat = explode(",", $request->nama_bangkom)[2];
            $usulan->id_diklat = explode(",", $request->nama_bangkom)[3];
            $usulan->id_siasn = explode(",", $request->nama_bangkom)[4];
            $usulan->sertifikat_siasn = explode(",", $request->nama_bangkom)[5];
            $usulan->nama_diklat = $request->nama_diklat;

            $usulan->save();

            if (is_null($request->file('file_spt'))) {
                $input['file_spt'] = $pengiriman->file_spt;
            } else {
                if (File::exists(public_path('Lamp_SPT/' . $pengiriman->file_spt))) {
                    File::delete(public_path('Lamp_SPT/' . $pengiriman->file_spt));
                }

                $spt_ext = $request->file('file_spt')->getClientOriginalExtension();
                $file = $request->file('file_spt');
                $fileName = $nip . '_SPT_' . $request->tgl_surat . $uniq_id . '.' . $spt_ext;
                $destinationPath = public_path() . '/Lamp_SPT';
                $file->move($destinationPath, $fileName);
                $input['file_spt'] = $fileName;
            }
            $pengiriman->update($input);
            return redirect()->route('pengiriman')
                ->with('success', 'Berhasil mengubah pengiriman');
        } catch (\Throwable $th) {
            return redirect()->route('pengiriman')
                ->with('error', 'Pengiriman gagal diubah' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengiriman  $pengiriman
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $id_usul = Pengiriman::find($id)->id_usul;
        $laporan = LaporanModel::where('id_usul', $id_usul)->first();

        if ($laporan != null) {
            // return redirect()->route('pengiriman')
            //     ->with('error', 'Gagal menghapus pengiriman, data sudah ada di Laporan');
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus pengiriman, data sudah ada di Laporan',
            ]);
        } else {
            $pengiriman = Pengiriman::find($id);
            if (File::exists(public_path('surat_pengiriman/' . $pengiriman->file_spt))) {
                File::delete(public_path('surat_pengiriman/' . $pengiriman->file_spt));
            }
            $pengiriman->delete();
            // return redirect()->route('pengiriman')
            //     ->with('success', 'Berhasil menghapus pengiriman');
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus pengiriman',
            ]);
        }
    }

    public function redirectAddPengiriman()
    {
        return redirect()->route('usulan_bangkom')
            ->with('info', "Untuk menambahkan Pengiriman, <br> Silahkan masuk pada Tab <b>Disetujui</b> dan pilih Kolom <b>Pengiriman</b> lalu klik Icon  <i class='fa-regular fa-paper-plane'></i></a>");
    }

    private function getIDPD()
    {
        if (auth()->user()->can('list-all-perangkat-daerah')) {
            $orderBy = "desc";
            if (session()->has('id_perangkat_daerah')) {
                $id = session()->get('id_perangkat_daerah');
                if ($id != 'all') {
                    $id_perangkat_daerah = $id;
                } else {
                    $id_perangkat_daerah = '';
                }
            } else {
                $id_perangkat_daerah = '';
            }
        } else {
            $orderBy = "desc";
            $id_perangkat_daerah = checkIdPerangkatDaerah();
        }

        return $id_perangkat_daerah;
    }

    /* Filter */
    private function getFilterNamaDiklat()
    {
        if (session()->has('filter_nama_diklat')) {
            if (session()->has('filter_nama_diklat') != '') {
                $filter_nama_diklat = session()->get('filter_nama_diklat');
            } else {
                $filter_nama_diklat = '';
            }
        } else {
            $filter_nama_diklat = '';
        }

        return $filter_nama_diklat;
    }

    private function getFilterNamaNip()
    {
        if (session()->has('filter_nama_nip')) {
            if (session()->has('filter_nama_nip') != '') {
                $filter_nama_nip = session()->get('filter_nama_nip');
            } else {
                $filter_nama_nip = '';
            }
        } else {
            $filter_nama_nip = '';
        }

        return $filter_nama_nip;
    }

    /* End Filter */

    public function export()
    {
        $id_perangkat_daerah = $this->getIDPD();
        return Excel::download(new PengirimanExport($id_perangkat_daerah), 'Pengiriman_Bangkom.xlsx');
    }

    public function PengirimanDilaksanakan($start, $end)
    {
        $id_perangkat_daerah = $this->getIDPD();

        $data = [
            'data' => Pengiriman::join('usulan', 'pengiriman.id_usul', '=', 'usulan.id_usul')
                ->join('pegawai', 'pengiriman.nip', '=', 'pegawai.nip')
                ->select('pengiriman.*', 'pengiriman.status as stt', 'usulan.jenis_diklat', 'usulan.sub_jenis_diklat', 'usulan.rumpun_diklat', 'usulan.nama_diklat', 'pegawai.nama_lengkap')
                ->where('pengiriman.status', '=', '0')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('usulan.nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->orderBy('pengiriman.entry_time', 'asc')
                ->skip($start)
                ->take($end)
                ->get(),
            'row_count' => Pengiriman::join('usulan', 'pengiriman.id_usul', '=', 'usulan.id_usul')
                ->join('pegawai', 'pengiriman.nip', '=', 'pegawai.nip')
                ->where('pengiriman.status', '=', '0')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('usulan.nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->orderBy('pengiriman.entry_time', 'asc')
                ->count(),

        ];

        return $data;
    }

    public function PengirimanSelesai($start, $end)
    {
        $id_perangkat_daerah = $this->getIDPD();

        $data = [
            'data' => Pengiriman::join('usulan', 'pengiriman.id_usul', '=', 'usulan.id_usul')
                ->join('laporan', 'pengiriman.id_usul', '=', 'laporan.id_usul', 'left outer')
                ->join('pegawai', 'pengiriman.nip', '=', 'pegawai.nip')
                ->select('pengiriman.*', 'pengiriman.status as stt', 'usulan.jenis_diklat', 'usulan.sub_jenis_diklat', 'usulan.rumpun_diklat', 'usulan.nama_diklat', 'laporan.nip as laporan_nip', 'pegawai.nama_lengkap')
                ->whereNotNull('laporan.id_usul')
                ->where('pengiriman.status', '=', '1')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('usulan.nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $filterNamaNip = $this->getFilterNamaNip();
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filterNamaNip . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $filterNamaNip . '%');
                })
                ->orderBy('pengiriman.entry_time', 'asc')
                ->skip($start)
                ->take($end)
                ->get(),
            'row_count' => Pengiriman::join('usulan', 'pengiriman.id_usul', '=', 'usulan.id_usul')
                ->join('laporan', 'pengiriman.id_usul', '=', 'laporan.id_usul', 'left outer')
                ->join('pegawai', 'pengiriman.nip', '=', 'pegawai.nip')
                ->whereNotNull('laporan.id_usul')
                ->where('pengiriman.status', '=', '1')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('usulan.nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $filterNamaNip = $this->getFilterNamaNip();
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filterNamaNip . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $filterNamaNip . '%');
                })
                ->orderBy('pengiriman.entry_time', 'asc')
                ->count(),
        ];

        return $data;
    }
}
