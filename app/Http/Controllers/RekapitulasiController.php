<?php

namespace App\Http\Controllers;

use com_exception;
use App\Models\UsulanModel;
use App\Models\LaporanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekapitulasiController extends Controller
{

    private $tahun = [];

    public function __construct()
    {
        $this->tahun = range(date('Y'), 2020);
    }

    /* Rekapitulasi Laporan */
    public function lapBerdasarPerangkatDaerah(Request $request)
    {
        $perangkat_daerah = getPerangkatDaerah();
        $laporanPerSKPD = [];
        foreach ($perangkat_daerah->Data as $pd) {
            $laporanPerSKPD[] = [
                'nama_perangkat_daerah' => $pd->perangkat_daerah,
                'row_count' => LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('laporan.entry_time', $request->tahun);
                })->count(),
                'laporan_ditinjau' => LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('laporan.entry_time', $request->tahun);
                })->where('status', null)->count(),
                'laporan_disetujui' => LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('laporan.entry_time', $request->tahun);
                })->where('status', '1')->count(),
                'laporan_ditolak' => LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('laporan.entry_time', $request->tahun);
                })->where('status', '0')->count(),
            ];
        }
        // total row_count, laporan_ditinjau, laporan_disetujui, laporan_ditolak
        $total_row_count = 0;
        $total_laporan_ditinjau = 0;
        $total_laporan_disetujui = 0;
        $total_laporan_ditolak = 0;
        foreach ($laporanPerSKPD as $lps) {
            $total_row_count += $lps['row_count'];
            $total_laporan_ditinjau += $lps['laporan_ditinjau'];
            $total_laporan_disetujui += $lps['laporan_disetujui'];
            $total_laporan_ditolak += $lps['laporan_ditolak'];
        }

        $data = [
            'title' => 'Rekapitulasi Laporan',
            'laporanPerSKPD' => $laporanPerSKPD,
            'tahun' => $this->tahun,
            'tahunSelected' => $request->tahun,
        ];
        return view('rekapitulasi.laporan.perangkat_daerah', $data, compact('total_row_count', 'total_laporan_ditinjau', 'total_laporan_disetujui', 'total_laporan_ditolak'));
    }

    public function lapBerdasarAsn(Request $request)
    {

        $perangkat_daerah = getPerangkatDaerah();
        $laporanPerSKPD = [];
        foreach ($perangkat_daerah->Data as $pd) {

            $laporanPerSKPD[] = [
                'nama_perangkat_daerah' => $pd->perangkat_daerah,
                'pegawai_row_count' => LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->distinct('pegawai.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('laporan.entry_time', $request->tahun);
                })->count(),
                'asn_disetujui_count' => LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->distinct('pegawai.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->where('status', '1')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('laporan.entry_time', $request->tahun);
                })->count(),
            ];
        }

        // total pegawai_row_count
        $total_pegawai_row_count = 0;
        $total_asn_disetujui = 0;
        foreach ($laporanPerSKPD as $lps) {
            $total_pegawai_row_count += $lps['pegawai_row_count'];
            $total_asn_disetujui += $lps['asn_disetujui_count'];
        }

        $data = [
            'title' => 'Rekapitulasi Laporan',
            'laporanPerSKPD' => $laporanPerSKPD,
            'total' => $total_pegawai_row_count,
            'total_asn_disetujui' => $total_asn_disetujui,
            'tahun' => $this->tahun,
            'tahunSelected' => $request->tahun,
        ];
        return view('rekapitulasi.laporan.pegawai_asn', $data);
    }

    public function lapBerdasarWaktu(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        // Ambil data
        $dataLaporanPaginated = LaporanModel::select(DB::raw('DATE(edit_time) as edit_date'), 'status', DB::raw('count(*) as jumlah'))
            ->whereNotNull('edit_time')
            ->groupBy(DB::raw('DATE(edit_time)'), 'status')
            ->orderBy(DB::raw('DATE(edit_time)'), 'desc')
            ->whereYear('edit_time', $tahun)
            ->whereMonth('edit_time', $bulan)
            ->get();

        // jika tidak ada data, kembalikan ke halaman sebelumnya dengan pesan error
        if ($dataLaporanPaginated->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang ditemukan');
        }

        // Loop melalui data yang telah diambil dari database
        foreach ($dataLaporanPaginated as $dl) {
            $editDateKey = (string)$dl->edit_date;

            if (!isset($laporanPerWaktu[$editDateKey])) {
                $laporanPerWaktu[$editDateKey] = [
                    'waktu' => $dl->edit_date,
                    'laporan_ditinjau' => 0,
                    'laporan_disetujui' => 0,
                    'laporan_ditolak' => 0,
                    'laporan_perbaikan' => 0,
                    'row_count' => 0,
                ];
            }

            // Isi array dengan jumlah laporan berdasarkan status
            switch ($dl->status) {
                    // case null:

                    //     $countDitinjau = LaporanModel::where('entry_time', 'LIKE', '%' . $editDateKey . '%')->where('status', null)->count();
                    //     $laporanPerWaktu[$editDateKey]['laporan_ditinjau'] = $countDitinjau;

                    //     // $laporanPerWaktu[$editDateKey]['laporan_ditinjau'] = $dl->jumlah;
                    //     break;
                case '1':
                    $laporanPerWaktu[$editDateKey]['laporan_disetujui'] = $dl->jumlah;
                    break;
                case '0':
                    $laporanPerWaktu[$editDateKey]['laporan_ditolak'] = $dl->jumlah;
                    break;
                case '2':
                    $laporanPerWaktu[$editDateKey]['laporan_perbaikan'] = $dl->jumlah;
                    break;
            }

            $laporanPerWaktu[$editDateKey]['row_count'] += $dl->jumlah;
        }

        $laporanPerWaktu = array_values($laporanPerWaktu);

        $data = [
            'title' => 'Rekapitulasi Laporan',
            'laporanPerWaktu' => $laporanPerWaktu,
        ];

        return view('rekapitulasi.laporan.waktu', $data, compact('bulan', 'tahun'));
    }

    public function lapBerdasarJenisDiklat(Request $request)
    {
        $reff_sub_jenis_diklat = getData('master_diktekfungs', 99)->Data;
        $laporanPerJenisDiklat = [];

        foreach ($reff_sub_jenis_diklat as $rj) {
            $laporanPerJenisDiklat[] = [
                'nama_sub_jenis_diklat' => $rj->jenis_diktekfungs,
                'row_count' => laporanModel::where('laporan.sub_jenis_diklat', 'LIKE', '%' . $rj->jenis_diktekfungs . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('laporan.entry_time', $request->tahun);
                })->count(),
            ];
        }

        $total = 0;
        foreach ($laporanPerJenisDiklat as $lps) {
            $total += $lps['row_count'];
        }

        $data = [
            'title' => 'Rekapitulasi Laporan',
            'laporanPerJenisDiklat' => $laporanPerJenisDiklat,
            'tahun' => $this->tahun,
            'tahunSelected' => $request->tahun,
        ];

        return view('rekapitulasi.laporan.jenis_diklat', $data, compact('total'));
    }

    /* Rekapitulasi Usulan */
    public function usulBerdasarSumber(Request $request)
    {

        $perangkat_daerah = getPerangkatDaerah();
        $usulanPerSKPD = [];
        foreach ($perangkat_daerah->Data as $pd) {
            $usulanPerSKPD[] = [
                'nama_perangkat_daerah' => $pd->perangkat_daerah,
                'row_count' => UsulanModel::join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('usulan.entry_time', $request->tahun);
                })->count(),
                'akd' => UsulanModel::join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('usulan.entry_time', $request->tahun);
                })->where('usulan.dasar_usulan', 'Analisis Kebutuhan Diklat (AKD)')->count(),
                'hcdp' => UsulanModel::join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('usulan.entry_time', $request->tahun);
                })->where('usulan.dasar_usulan', 'Human Capital Development Plan (HCDP)')->count(),
                'penawaran' => UsulanModel::join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('id_perangkat_daerah', 'LIKE', $pd->id_perangkat_daerah . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('usulan.entry_time', $request->tahun);
                })->where('usulan.dasar_usulan', 'Penawaran')->count(),
            ];
        }

        // total akd, hcdp, penawaran
        $row_count = 0;
        $total_akd = 0;
        $total_hcdp = 0;
        $total_penawaran = 0;
        foreach ($usulanPerSKPD as $ups) {
            $row_count += $ups['row_count'];
            $total_akd += $ups['akd'];
            $total_hcdp += $ups['hcdp'];
            $total_penawaran += $ups['penawaran'];
        }

        $data = [
            'title' => 'Rekapitulasi Usulan',
            'usulanPerSKPD' => $usulanPerSKPD,
            'total_akd' => $total_akd,
            'total_hcdp' => $total_hcdp,
            'total_penawaran' => $total_penawaran,
            'row_count' => $row_count,
            'tahun' => $this->tahun,
            'tahunSelected' => $request->tahun,
        ];

        return view('rekapitulasi.usulan.sumber_dana', $data);
    }

    public function usulBerdasarJenisDiklat(Request $request)
    {
        $reff_sub_jenis_diklat = getData('master_diktekfungs', 99)->Data;
        $usulanPerJenisDiklat = [];

        foreach ($reff_sub_jenis_diklat as $rj) {
            $usulanPerJenisDiklat[] = [
                'nama_sub_jenis_diklat' => $rj->jenis_diktekfungs,
                'row_count' => UsulanModel::where('usulan.sub_jenis_diklat', 'LIKE', '%' . $rj->jenis_diktekfungs . '%')->when($request->tahun, function ($query) use ($request) {
                    return $query->whereYear('usulan.entry_time', $request->tahun);
                })->count(),
            ];
        }

        $total = 0;
        foreach ($usulanPerJenisDiklat as $lps) {
            $total += $lps['row_count'];
        }

        $data = [
            'title' => 'Rekapitulasi Usulan',
            'usulanPerJenisDiklat' => $usulanPerJenisDiklat,
            'tahun' => $this->tahun,
            'tahunSelected' => $request->tahun,
        ];

        return view('rekapitulasi.usulan.jenis_diklat', $data, compact('total'));
    }

    public function loginTerbanyak()
    {
        $data = [
            'title' => 'Rekapitulasi Login Terbanyak',
            'users' => DB::table('users')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->join('pegawai', 'users.user_id', '=', 'pegawai.nip', 'left outer')
                ->select('users.*', 'roles.name as role_name', 'pegawai.nama_lengkap', 'pegawai.perangkat_daerah', 'pegawai.id_perangkat_daerah as id_perangkat_daerah_master', 'pegawai.jabatan', 'pegawai.satuan_organisasi')
                ->orderBy('users.login_count', 'desc')
                ->get(),
        ];

        return view('rekapitulasi.login.login_terbanyak', $data);
    }

    public function loginTerbaru()
    {
        $data = [
            'title' => 'Rekapitulasi Login Terbaru',
            'users' => DB::table('users')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->join('pegawai', 'users.user_id', '=', 'pegawai.nip', 'left outer')
                ->select('users.*', 'roles.name as role_name', 'pegawai.nama_lengkap', 'pegawai.perangkat_daerah', 'pegawai.id_perangkat_daerah as id_perangkat_daerah_master', 'pegawai.jabatan', 'pegawai.satuan_organisasi')
                ->orderBy('users.login_time', 'desc')
                ->get(),
        ];

        return view('rekapitulasi.login.login_terbaru', $data);
    }

    public function aktifitasLaporan()
    {

        $combinedQuery = DB::table('laporan')
            ->select(
                'pegawai.nip',
                'pegawai.nama_lengkap',
                'pegawai.satuan_organisasi',
                'pegawai.perangkat_daerah',
                DB::raw('SUM(CASE WHEN subquery.status = "edit" THEN 1 ELSE 0 END) as totalEdit'),
                DB::raw('SUM(CASE WHEN subquery.status = "create" THEN 1 ELSE 0 END) as totalCreate')
            )
            ->join('pegawai', 'subquery.user', '=', 'pegawai.nip')
            ->from(function ($query) {
                $query->select(
                    'entry_user as user',
                    'entry_time as time',
                    DB::raw("'create' as status")
                )
                    ->from('laporan')
                    ->unionAll(
                        DB::table('laporan')
                            ->select(
                                'edit_user as user',
                                'edit_time as time',
                                DB::raw("'edit' as status")
                            )
                    );
            }, 'subquery')
            ->groupBy('pegawai.nip', 'pegawai.nama_lengkap')
            ->get();

        // dd($combinedQuery);

        $data = [
            'title' => 'Aktivitas Laporan User',
            'data' => $combinedQuery,
        ];

        return view('rekapitulasi.laporan.aktivitas_laporan', $data);
    }
}
