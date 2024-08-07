<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pengiriman;
use App\Models\UsulanModel;
use App\Models\LaporanModel;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsulanExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UsulanController extends Controller
{

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

        $data = [
            'title' => 'Daftar Usulan Pengembangan Kompetensi',
        ];

        if ($request->has('nip')) {
            $nip = $request->nip;
            $identitas = putData('ident_peg', $nip)->Data;
            $kompetensi = putData('personal', $nip)->Data;
            redirect()
                ->route('usulan_bangkom')
                ->with('identitas', $identitas)
                ->with('kompetensi', $kompetensi);
        }

        return view('usulan_bangkom.index', $data);
    }

    public function form_usulan(Request $request)
    {
        try {
            $data = [
                'title' => 'Form Usulan Pengembangan Kompetensi',
                'perangkat_daerah' => getData('master_perangkat_daerah', 1),
            ];

            if ($request->has('id_perangkat_daerah')) {

                if (auth()->user()->can('search-option-disabled')) {
                    $id_perangkat_daerah = checkIdPerangkatDaerah();
                } else {
                    $id_perangkat_daerah = $request->id_perangkat_daerah;
                }

                $data['usulan'] = PegawaiModel::where('id_perangkat_daerah', 'like', $id_perangkat_daerah . '%')
                    ->where(function ($query) use ($request) {
                        $query->where('nama_lengkap', 'like', '%' . $request->nama . '%')
                            ->orWhere('nip', 'like', '%' . $request->nama . '%');
                    })
                    ->get();

                if (empty($data['usulan'])) {
                    return redirect()
                        ->route('laporan.form_laporan')
                        ->with('error', 'Data Pegawai tidak ditemukan');
                }
            }
            return view('usulan_bangkom.form_usulan', $data);
        } catch (\Throwable $th) {
            return redirect()
                ->route('form_usulan')
                ->with('error', 'Terjadi kesalahan');
        }
    }

    public function create($nip)
    {

        $pegawai = PegawaiModel::where('nip', $nip)->first();
        if (empty($pegawai)) {
            return redirect()
                ->route('usulan_bangkom')
                ->with('error', 'Data Pegawai tidak ditemukan');
        }

        $data = [
            'title' => 'Usulan Pengembangan Kompetensi',
            'pegawai' => $pegawai,
            'jenis_diklat' => DB::table('diklat')
                ->select('jenis_diklat')
                ->distinct()
                ->get(),
        ];

        return view('usulan_bangkom.create', $data);
    }

    public function store(Request $request)
    {


        try {
            $this->validate($request, [
                'nip' => 'required|numeric',
                'nama' => 'required',
                'nama_diklat' => 'required',
            ]);

            // XSS Filtering & Sanitizing Input Data
            $request->merge(array_map('strip_tags', $request->all()));
            $request->merge(array_map('trim', $request->all()));

            $input = new UsulanModel();
            $input->nip = $request->nip;
            $input->nama = $request->nama;

            $diklat = explode(',', $request->rumpun_diklat);

            $input->jenis_diklat = $request->jenis_diklat;
            $input->sub_jenis_diklat = $request->sub_jenis_diklat;
            $input->rumpun_diklat = $diklat[0];
            $input->id_diklat = $diklat[1];
            $input->id_siasn = $diklat[2];
            $input->sertifikat_siasn = $diklat[3];
            $input->dasar_usulan = $request->dasar_usulan;
            $input->nama_diklat = $request->nama_diklat;
            $input->status = null;

            $uniq_id = Carbon::now()->timestamp;

            if ($request->hasFile('file_surat_penawaran')) {
                $sttpp_ext = $request->file('file_surat_penawaran')->getClientOriginalExtension();
                $file = $request->file('file_surat_penawaran');
                $fileName = $request->nip . '_SuratPenawaran_' . $request->tgl_sttpp . '_' . $uniq_id . '.' . $sttpp_ext;
                $destinationPath = public_path() . '/Lamp_Surat_Penawaran';
                $file->move($destinationPath, $fileName);
                $input['file_surat_penawaran'] = $fileName;
            }

            $input->save();

            return redirect()
                ->route('usulan_bangkom')
                ->with('success', 'Berhasil menambahkan usulan');
        } catch (\Throwable $th) {
            return redirect()
                ->route('usulan_bangkom')
                ->with('error', 'Gagal menambahkan usulan' . $th->getMessage());
        }
    }

    public function update_status(Request $request, $nip, $id)
    {
        try {
            $data = [
                'title' => 'Perubahan Status Pengembangan Kompetensi',
                'usulan' => DB::table('usulan')
                    ->join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                    ->where('usulan.id_usul', $id)
                    ->where('usulan.nip', $nip)
                    ->get()
                    ->first(),
                // 'master_diklat' => getData('master_diklat', 1),
                'jenis_diklat' => DB::table('diklat')
                    ->select('jenis_diklat')
                    ->distinct()
                    ->get(),
            ];

            if ($data['usulan'] != null) {
                return view('usulan_bangkom.update_status', $data);
            } else {
                return redirect()
                    ->route('usulan_bangkom')
                    ->with('error', 'Data tidak ditemukan');
            }
        } catch (\Throwable $th) {
            return redirect()
                ->route('usulan_bangkom')
                ->with('error', 'Terjadi kesalahan' . $th->getMessage());
        }
    }

    public function edit_status(Request $request, $id)
    {

        try {
            $usulan = UsulanModel::find($id);
            $usulan->status = $request->status;

            try {
                $request->merge(array_map('strip_tags', $request->all()));
                $request->merge(array_map('trim', $request->all()));
            } catch (\Throwable $th) {
                return redirect()
                    ->route('usulan_bangkom')
                    ->with('error', 'Terjadi kesalahan');
            }

            $usulan->alasan = $request->alasan;
            $usulan->nama_diklat = $request->nama_diklat;

            $diklat = explode(',', $request->rumpun_diklat);
            $usulan->jenis_diklat = $request->jenis_diklat;
            $usulan->sub_jenis_diklat = $request->sub_jenis_diklat;
            $usulan->dasar_usulan = $request->dasar_usulan;
            $usulan->rumpun_diklat = $diklat[0];
            $usulan->id_diklat = $diklat[1];
            $usulan->id_siasn = $diklat[2];
            $usulan->sertifikat_siasn = $diklat[3];

            if (is_null($request->file('file_surat_penawaran'))) {
                $usulan->file_surat_penawaran = $usulan->file_surat_penawaran;
            } else {
                if (File::exists(public_path('Lamp_Surat_Penawaran/' . $usulan->file_surat_penawaran))) {
                    File::delete(public_path('Lamp_Surat_Penawaran/' . $usulan->file_surat_penawaran));
                }

                $uniq_id = Carbon::now()->timestamp;
                $spt_ext = $request->file('file_surat_penawaran')->getClientOriginalExtension();
                $file = $request->file('file_surat_penawaran');
                $fileName = $request->nip . '_SuratPenawaran_' . $request->tgl_sttpp . $uniq_id . '.' . $spt_ext;
                $destinationPath = public_path() . '/Lamp_Surat_Penawaran';
                $file->move($destinationPath, $fileName);
                $usulan->file_surat_penawaran = $fileName;
            }

            $usulan->save();

            return redirect()
                ->route('usulan_bangkom')
                ->with('success', 'Berhasil mengubah status usulan');
        } catch (\Throwable $th) {
            return redirect()
                ->route('usulan_bangkom')
                ->with('error', 'Gagal mengubah status usulan ' . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // check data in table pengiriman and laporan
            $pengiriman = Pengiriman::where('id_usul', $id)->first();
            $laporan = LaporanModel::where('id_usul', $id)->first();
            $usulan = UsulanModel::find($id);

            if ($pengiriman != null || $laporan != null) {
                $textError = $laporan != null ? 'Laporan' : '';
                $textPengiriman = $pengiriman != null ? 'dan Pengiriman' : '';
                return redirect()
                    ->route('usulan_bangkom')
                    ->with('error', "Gagal menghapus usulan, Data sudah ada pada $textError $textPengiriman ");
            } else {
                if (File::exists(public_path('Lamp_Surat_Penawaran/' . $usulan->file_surat_penawaran))) {
                    File::delete(public_path('Lamp_Surat_Penawaran/' . $usulan->file_surat_penawaran));
                }
                $usulan->delete();
                // return redirect()
                //     ->route('usulan_bangkom')
                //     ->with('success', 'Berhasil menghapus usulan');
                // json response success
                return response()->json(['message' => 'Berhasil menghapus usulan']);
            }
        } catch (\Throwable $th) {
            // return redirect()
            //     ->route('usulan_bangkom')
            //     ->with('error', 'Gagal menghapus usulan');
            // json response error
            return response()->json(['error' => 'Gagal menghapus usulan'], 500);
        }
    }

    public function redirectAddUsulan()
    {
        return redirect()
            ->route('form_usulan')
            ->with('info', 'Untuk menambahkan Usulan, <br> Pilih pada form pencarian Perangkat Daerah dan Nama/NIP yang tersedia dibawah ini');
    }

    public function dataSubJenisDiklat($jenis_diklat)
    {
        $data = DB::table('diklat')
            ->select('sub_jenis_diklat')
            ->where('jenis_diklat', $jenis_diklat)
            ->distinct()
            ->get();
        return $data;
    }

    public function dataRumpunDiklat(Request $request)
    {
        $data = DB::table('diklat')
            ->select('rumpun_diklat', 'id_diklat', 'id_siasn', 'sertifikat_siasn')
            ->where('jenis_diklat', $request->jenis_diklat)
            ->where('sub_jenis_diklat', $request->sub_jenis_diklat)
            ->distinct()
            ->get();
        return $data;
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

    public function UsulanDitinjau($start, $end)
    {
        $id_perangkat_daerah = $this->getIDPD();

        $data = [
            'data' => UsulanModel::join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                ->select('usulan.*', 'pegawai.nama_lengkap')
                ->where('usulan.status', null)
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->orderBy('usulan.entry_time', 'asc')
                ->skip($start)
                ->take($end)
                ->get(),
            'row_count' => UsulanModel::join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                ->where('usulan.status', null)
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->orderBy('usulan.entry_time', 'asc')
                ->count(),

        ];

        return $data;
    }
    public function UsulanDilaksanakan($start, $end)
    {
        $id_perangkat_daerah = $this->getIDPD();

        $data = [
            'data' => UsulanModel::join('pengiriman', 'usulan.id_usul', '=', 'pengiriman.id_usul', 'left outer')
                ->join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                ->select('usulan.*', 'pegawai.nama_lengkap')
                ->where('pengiriman.id_usul', '!=', null)
                ->where('usulan.status', '1')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->orderBy('usulan.entry_time', 'asc')
                ->skip($start)
                ->take($end)
                ->get(),
            'row_count' => UsulanModel::join('pengiriman', 'usulan.id_usul', '=', 'pengiriman.id_usul', 'left outer')
                ->join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                ->where('pengiriman.id_usul', '!=', null)
                ->where('usulan.status', '1')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->count(),
        ];

        return $data;
    }

    public function usulanDisetujui($start, $end)
    {
        $id_perangkat_daerah = $this->getIDPD();

        $data = [
            'data' => UsulanModel::join('pengiriman', 'usulan.id_usul', '=', 'pengiriman.id_usul', 'left outer')
                ->join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                ->select('usulan.*', 'pegawai.nama_lengkap')
                ->where('pengiriman.id_usul', '=', null)
                ->where('usulan.status', '1')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->orderBy('usulan.entry_time', 'asc')
                ->skip($start)
                ->take($end)
                ->get(),
            'row_count' => UsulanModel::join('pengiriman', 'usulan.id_usul', '=', 'pengiriman.id_usul', 'left outer')
                ->join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                ->where('pengiriman.id_usul', '=', null)
                ->where('usulan.status', '1')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->count(),
        ];

        return $data;
    }

    public function Usulanditolak($start, $end)
    {
        $id_perangkat_daerah = $this->getIDPD();

        $data = [
            'data' => UsulanModel::leftJoin('pengiriman', function ($join) {
                $join->on('usulan.id_usul', '=', 'pengiriman.id_usul')
                    ->whereNull('pengiriman.id_usul');
            })
                ->join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                ->select('usulan.*', 'pegawai.nama_lengkap')
                ->where('usulan.status', '0')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->orderBy('usulan.entry_time', 'asc')
                ->skip($start)
                ->take($end)
                ->get(),

            'row_count' => UsulanModel::leftJoin('pengiriman', function ($join) {
                $join->on('usulan.id_usul', '=', 'pengiriman.id_usul')
                    ->whereNull('pengiriman.id_usul');
            })
                ->join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                ->where('usulan.status', '0')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where('nama_diklat', 'LIKE', '%' . $this->getFilterNamaDiklat() . '%')
                ->where(function ($query) {
                    $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $this->getFilterNamaNip() . '%')
                        ->orWhere('pegawai.nip', 'LIKE', '%' . $this->getFilterNamaNip() . '%');
                })
                ->orderBy('usulan.entry_time', 'asc')
                ->skip($start)
                ->take($end)
                ->count(),
        ];

        return $data;
    }

    public function export()
    {
        $id_perangkat_daerah = $this->getIDPD();
        return Excel::download(new UsulanExport($id_perangkat_daerah), 'Usulan_Bangkom.xlsx');
    }
}
