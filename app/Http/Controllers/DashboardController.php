<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsulanModel;
use App\Models\LaporanModel;
use App\Models\Pengiriman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    private $nama_nip = null;

    public function index()
    {

        if (auth()->user()->can('personal-locked')) {
            $this->nama_nip = Auth::user()->user_id;
        }

        // dd($this->nama_nip);

        if (auth()->user()->can('list-all-perangkat-daerah')) {
            $total_usulan = UsulanModel::count();
            $total_pengiriman = Pengiriman::count();
            $total_laporan = LaporanModel::count();

            $usulanDitolak = UsulanModel::where('status', "0")->count();

            $usulanDisetujui = UsulanModel::where('status', "1")->count();
            $usulanDilaksanakan = UsulanModel::where('status', "9")->count();
            $usulanDitinjau = UsulanModel::where('status', NULL)->count();

            $dataPengirimanDikirim = Pengiriman::where('status', "0")->count();
            $dataPengirimanSelesai = Pengiriman::where('status', "1")->count();

            $laporanDitolak = LaporanModel::where('status', "0")->count();
            $laporanDisetujui = LaporanModel::where('status', "1")->count();
            $laporanDilaksanakan = LaporanModel::where('status', "9")->count();
            $laporanDitinjau = LaporanModel::where('status', NULL)->count();
            $laporanDiperbaiki = LaporanModel::where('status', '2')->count();

            //statistik usulan per bulan
            $usulanByMonth = DB::table('usulan')
                ->selectRaw('count(id_usul) as `data`')
                ->selectRaw("DATE_FORMAT(entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(entry_time, '%Y') year")
                ->whereRaw("entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countusulanMonth = count($usulanByMonth);
            $listDataUsulan = [];
            $listMonthUsulan = [];
            for ($i = 0; $i < $countusulanMonth; $i++) {
                $listDataUsulan[$i] = $usulanByMonth[$i]->data;
                $listMonthUsulan[$i] = $usulanByMonth[$i]->month_string;
            }

            //statistik laporan per bulan
            $laporanByMonth = DB::table('laporan')
                ->selectRaw('count(id_lapor) as `data`')
                ->selectRaw("DATE_FORMAT(entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(entry_time, '%Y') year")
                ->whereRaw("entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countLaporanMonth = count($laporanByMonth);
            $listDataLaporan = [];
            $listMonthLaporan = [];
            for ($i = 0; $i < $countLaporanMonth; $i++) {
                $listDataLaporan[$i] = $laporanByMonth[$i]->data;
                $listMonthLaporan[$i] = $laporanByMonth[$i]->month_string;
            }

            // statistik pengiriman per bulan
            $pengirimanByMonth = DB::table('pengiriman')
                ->selectRaw('count(id_pengiriman) as `data`')
                ->selectRaw("DATE_FORMAT(entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(entry_time, '%Y') year")
                ->whereRaw("entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countPengirimanMonth = count($pengirimanByMonth);
            $listDataPengiriman = [];
            $listMonthPengiriman = [];
            for ($i = 0; $i < $countPengirimanMonth; $i++) {
                $listDataPengiriman[$i] = $pengirimanByMonth[$i]->data;
                $listMonthPengiriman[$i] = $pengirimanByMonth[$i]->month_string;
            }

            $pengirimanDilaksanakan = DB::table('pengiriman')
                ->selectRaw('count(id_pengiriman) as `dilaksanakan`')
                ->selectRaw("DATE_FORMAT(entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(entry_time, '%Y') year")
                ->whereRaw("entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->where('status', "0")
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countPengirimanDilaksanakan = count($pengirimanDilaksanakan);
            $listPengirimanDilaksanakan = [];
            for ($i = 0; $i < $countPengirimanDilaksanakan; $i++) {
                $listPengirimanDilaksanakan[$i] = $pengirimanDilaksanakan[$i]->dilaksanakan;
            }

            $pengirimanSelesai = DB::table('pengiriman')
                ->selectRaw('count(id_pengiriman) as `selesai`')
                ->selectRaw("DATE_FORMAT(entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(entry_time, '%Y') year")
                ->whereRaw("entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->where('status', "1")
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countPengirimanSelesai = count($pengirimanSelesai);
            $listPengirimanSelesai = [];
            for ($i = 0; $i < $countPengirimanSelesai; $i++) {
                $listPengirimanSelesai[$i] = $pengirimanSelesai[$i]->selesai;
            }

            $data = [
                'title' => 'Dashboard',
                'total_usulan' => $total_usulan,
                'total_pengiriman' => $total_pengiriman,
                'total_laporan' => $total_laporan,
                'usulanDitolak' => $usulanDitolak,
                'usulanDisetujui' => $usulanDisetujui,
                'usulanDilaksanakan' => $usulanDilaksanakan,
                'usulanDitinjau' => $usulanDitinjau,
                'laporanDitolak' => $laporanDitolak,
                'laporanDisetujui' => $laporanDisetujui,
                'laporanDilaksanakan' => $laporanDilaksanakan,
                'laporanDitinjau' => $laporanDitinjau,
                'laporanDiperbaiki' => $laporanDiperbaiki,
                'listDataUsulan' => $listDataUsulan,
                'listMonthUsulan' => $listMonthUsulan,
                'listDataLaporan' => $listDataLaporan,
                'listMonthLaporan' => $listMonthLaporan,
                'listMonthPengiriman' => $listMonthPengiriman,
                'listPengirimanDilaksanakan' => $listPengirimanDilaksanakan,
                'listPengirimanSelesai' => $listPengirimanSelesai,
                'countPengirimanSelesai' => $dataPengirimanSelesai,
                'countPengirimanDilaksanakan' => $dataPengirimanDikirim,
            ];
        } else {
            $total_usulan = UsulanModel::join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->when($this->nama_nip, function ($query) {
                return $query->where('usulan.nip', $this->nama_nip);
            })->count();
            $total_pengiriman = Pengiriman::join('pegawai', 'pegawai.nip', '=', 'pengiriman.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->when($this->nama_nip, function ($query) {
                return $query->where('pengiriman.nip', $this->nama_nip);
            })->count();
            $total_laporan = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->when($this->nama_nip, function ($query) {
                return $query->where('pegawai.nip', $this->nama_nip);
            })->count();

            $dataPengirimanDikirim = Pengiriman::join('pegawai', 'pegawai.nip', '=', 'pengiriman.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('pengiriman.status', "0")->when($this->nama_nip, function ($query) {
                return $query->where('pengiriman.nip', $this->nama_nip);
            })->count();
            $dataPengirimanSelesai = Pengiriman::join('pegawai', 'pegawai.nip', '=', 'pengiriman.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('pengiriman.status', "1")->when($this->nama_nip, function ($query) {
                return $query->where('pengiriman.nip', $this->nama_nip);
            })->count();

            $usulanDitolak = UsulanModel::join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('status', "0")->when($this->nama_nip, function ($query) {
                return $query->where('usulan.nip', $this->nama_nip);
            })->count();
            $usulanDisetujui = UsulanModel::join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('status', "1")->when($this->nama_nip, function ($query) {
                return $query->where('usulan.nip', $this->nama_nip);
            })->count();
            $usulanDilaksanakan = UsulanModel::join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('status', "9")->when($this->nama_nip, function ($query) {
                return $query->where('usulan.nip', $this->nama_nip);
            })->count();
            $usulanDitinjau = UsulanModel::join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('status', NULL)->when($this->nama_nip, function ($query) {
                return $query->where('usulan.nip', $this->nama_nip);
            })->count();

            $laporanDitolak = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('status', "0")->when($this->nama_nip, function ($query) {
                return $query->where('laporan.nip', $this->nama_nip);
            })->count();
            $laporanDisetujui = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('status', "1")->when($this->nama_nip, function ($query) {
                return $query->where('laporan.nip', $this->nama_nip);
            })->count();
            $laporanDilaksanakan = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('status', "9")->when($this->nama_nip, function ($query) {
                return $query->where('laporan.nip', $this->nama_nip);
            })->count();
            $laporanDitinjau = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('status', NULL)->when($this->nama_nip, function ($query) {
                return $query->where('laporan.nip', $this->nama_nip);
            })->count();
            $laporanDiperbaiki = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')->where('status', "2")->when($this->nama_nip, function ($query) {
                return $query->where('laporan.nip', $this->nama_nip);
            })->count();

            //statistik usulan per bulan
            $usulanByMonth = DB::table('usulan')
                ->join('pegawai', 'pegawai.nip', '=', 'usulan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')
                ->selectRaw('count(id_usul) as `data`')
                ->selectRaw("DATE_FORMAT(usulan.entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(usulan.entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(usulan.entry_time, '%Y') year")
                ->whereRaw("usulan.entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->when($this->nama_nip, function ($query) {
                    return $query->where('usulan.nip', $this->nama_nip);
                })
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countusulanMonth = count($usulanByMonth);
            $listDataUsulan = [];
            $listMonthUsulan = [];
            for ($i = 0; $i < $countusulanMonth; $i++) {
                $listDataUsulan[$i] = $usulanByMonth[$i]->data;
                $listMonthUsulan[$i] = $usulanByMonth[$i]->month_string;
            }

            //statistik laporan per bulan
            $laporanByMonth = DB::table('laporan')
                ->join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')
                ->selectRaw('count(id_lapor) as `data`')
                ->selectRaw("DATE_FORMAT(laporan.entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(laporan.entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(laporan.entry_time, '%Y') year")
                ->whereRaw("laporan.entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->when($this->nama_nip, function ($query) {
                    return $query->where('laporan.nip', $this->nama_nip);
                })
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countLaporanMonth = count($laporanByMonth);
            $listDataLaporan = [];
            $listMonthLaporan = [];
            for ($i = 0; $i < $countLaporanMonth; $i++) {
                $listDataLaporan[$i] = $laporanByMonth[$i]->data;
                $listMonthLaporan[$i] = $laporanByMonth[$i]->month_string;
            }

            // statistik pengiriman per bulan
            $pengirimanByMonth = DB::table('pengiriman')
                ->join('pegawai', 'pegawai.nip', '=', 'pengiriman.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')
                ->selectRaw('count(pengiriman.id_pengiriman) as `data`')
                ->selectRaw("DATE_FORMAT(pengiriman.entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(pengiriman.entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(pengiriman.entry_time, '%Y') year")
                ->whereRaw("pengiriman.entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->when($this->nama_nip, function ($query) {
                    return $query->where('pengiriman.nip', $this->nama_nip);
                })
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countPengirimanMonth = count($pengirimanByMonth);
            $listDataPengiriman = [];
            $listMonthPengiriman = [];
            for ($i = 0; $i < $countPengirimanMonth; $i++) {
                $listDataPengiriman[$i] = $pengirimanByMonth[$i]->data;
                $listMonthPengiriman[$i] = $pengirimanByMonth[$i]->month_string;
            }

            $pengirimanDilaksanakan = DB::table('pengiriman')
                ->join('pegawai', 'pegawai.nip', '=', 'pengiriman.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')
                ->selectRaw('count(pengiriman.id_pengiriman) as `dilaksanakan`')
                ->selectRaw("DATE_FORMAT(pengiriman.entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(pengiriman.entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(pengiriman.entry_time, '%Y') year")
                ->whereRaw("pengiriman.entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->when($this->nama_nip, function ($query) {
                    return $query->where('pengiriman.nip', $this->nama_nip);
                })
                ->where('pengiriman.status', "0")
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countPengirimanDilaksanakan = count($pengirimanDilaksanakan);
            $listPengirimanDilaksanakan = [];
            for ($i = 0; $i < $countPengirimanDilaksanakan; $i++) {
                $listPengirimanDilaksanakan[$i] = $pengirimanDilaksanakan[$i]->dilaksanakan;
            }

            $pengirimanSelesai = DB::table('pengiriman')
                ->join('pegawai', 'pegawai.nip', '=', 'pengiriman.nip')->where('pegawai.id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')
                ->selectRaw('count(pengiriman.id_pengiriman) as `selesai`')
                ->selectRaw("DATE_FORMAT(pengiriman.entry_time, '%m') month")
                ->selectRaw("DATE_FORMAT(pengiriman.entry_time, '%M') month_string")
                ->selectRaw("DATE_FORMAT(pengiriman.entry_time, '%Y') year")
                ->whereRaw("pengiriman.entry_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 11 MONTH)  AND CURDATE() + INTERVAL 1 DAY")
                ->when($this->nama_nip, function ($query) {
                    return $query->where('pengiriman.nip', $this->nama_nip);
                })
                ->where('pengiriman.status', "1")
                ->groupBy('month')->orderBy('year')->orderBy('month')->get();
            $countPengirimanSelesai = count($pengirimanSelesai);
            $listPengirimanSelesai = [];
            for ($i = 0; $i < $countPengirimanSelesai; $i++) {
                $listPengirimanSelesai[$i] = $pengirimanSelesai[$i]->selesai;
            }

            $data = [
                'title' => 'Dashboard',
                'total_usulan' => $total_usulan,
                'total_pengiriman' => $total_pengiriman,
                'total_laporan' => $total_laporan,
                'usulanDitolak' => $usulanDitolak,
                'usulanDisetujui' => $usulanDisetujui,
                'usulanDilaksanakan' => $usulanDilaksanakan,
                'usulanDitinjau' => $usulanDitinjau,
                'laporanDitolak' => $laporanDitolak,
                'laporanDisetujui' => $laporanDisetujui,
                'laporanDilaksanakan' => $laporanDilaksanakan,
                'laporanDitinjau' => $laporanDitinjau,
                'laporanDiperbaiki' => $laporanDiperbaiki,
                'listDataUsulan' => $listDataUsulan,
                'listMonthUsulan' => $listMonthUsulan,
                'listDataLaporan' => $listDataLaporan,
                'listMonthLaporan' => $listMonthLaporan,
                'listMonthPengiriman' => $listMonthPengiriman,
                'listPengirimanDilaksanakan' => $listPengirimanDilaksanakan,
                'listPengirimanSelesai' => $listPengirimanSelesai,
                'countPengirimanSelesai' => $dataPengirimanSelesai,
                'countPengirimanDilaksanakan' => $dataPengirimanDikirim,
            ];
        }

        return view('dashboard.index', $data);
    }
}
