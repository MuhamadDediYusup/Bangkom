<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiklatModel;
use PDO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DiklatController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'Master Data Diklat',
            'diklat' => DB::table('diklat')->get(),
        ];

        return view('md_diklat.index', $data);
    }

    public function updateDataDiklat()
    {

        DiklatModel::truncate();

        $diklat = getData('master_diklat', 'mtf');

        if ($diklat->Data != null) {
            $diklat = $diklat->Data;
            foreach ($diklat as $key => $p) {
                $diklat = new diklatModel;
                $diklat->jenis_diklat = $p->jenis_diklat;
                $diklat->sub_jenis_diklat = $p->sub_jenis_diklat;
                $diklat->rumpun_diklat = $p->rumpun_diklat;
                $diklat->id_diklat = $p->id_diklat;
                $diklat->id_siasn = $p->id_siasn;
                $diklat->sertifikat_siasn = $p->sertifikat_siasn;
                $diklat->entry_time = Carbon::now()->toDateTimeString();
                $diklat->save();
            }
            return redirect()->back()->with('success', 'Data diklat berhasil diupdate');
        }
    }
}
