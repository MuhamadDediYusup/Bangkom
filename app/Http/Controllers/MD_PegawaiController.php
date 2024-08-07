<?php

namespace App\Http\Controllers;

use PDO;
use Carbon\Carbon;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Symfony\Component\Console\Input\Input;

class MD_PegawaiController extends Controller
{

    public function __construct()
    {
        set_time_limit(8000000);
    }

    public function index(Request $request)
    {
        // check database null or not
        $data = DB::table('pegawai')->get();

        $data = [
            'title' => 'Master Pegawai',
            'pegawai' => PegawaiModel::orderBy('id_perangkat_daerah','ASC','id_pegawai','ASC')->get(),
        ];

        return view('md_pegawai.index', $data);
    }

    public function updateDataPegawai()
    {

        PegawaiModel::truncate();

        $pegawai = putData('master_pegawai', '1');

        if ($pegawai->Data != null) {
            $pegawai = $pegawai->Data;
            foreach ($pegawai as $key => $p) {
                $pegawai = new PegawaiModel;
                $pegawai->nip = $p->nip;
                $pegawai->nama_lengkap = $p->nama_lengkap;
                $pegawai->jabatan = $p->jabatan;
                $pegawai->sub_satuan_organisasi = $p->sub_satuan_organisasi;
                $pegawai->satuan_organisasi = $p->satuan_organisasi;
                $pegawai->perangkat_daerah = $p->perangkat_daerah;
                $pegawai->id_perangkat_daerah = $p->id_perangkat_daerah;
                $pegawai->entry_time = Carbon::now()->toDateTimeString();
                $pegawai->save();
            }

            // PegawaiModel::insert($data);

            return redirect()->back()->with('success', 'Data Pegawai Berhasil Diupdate');
        }
    }
}
