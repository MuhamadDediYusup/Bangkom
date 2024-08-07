<?php

namespace App\Http\Controllers;

use App\Models\UsulanModel;
use App\Models\LaporanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModalController extends Controller
{
    public function getModalDetailAsn($nip, Request $request)
    {

        $nip = $request->nip;
        $data = [
            $identitas = putData('ident_peg', $nip)->Data,
            $kompetensi = putData('personal', $nip)->Data,
            $jamPelajaran = putData('20JP', '+nip+' . $nip)->Data[0],
            $usulan = DB::table('usulan')
                ->join('pengiriman', 'usulan.id_usul', '=', 'pengiriman.id_usul', 'left outer')
                ->select(DB::raw('if(pengiriman.status, 9, usulan.status) as status_usulan'), 'usulan.*')
                ->where('usulan.nip', $nip)
                ->get(),
            $laporan = DB::table('laporan')
                ->select('laporan.*', 'laporan.status as status_laporan')
                ->where('laporan.nip', '=', $nip)
                ->get(),
            $pengiriman = DB::table('pengiriman')
                ->join('usulan', 'pengiriman.id_usul', '=', 'usulan.id_usul')
                ->select('pengiriman.*', 'usulan.jenis_diklat', 'usulan.sub_jenis_diklat', 'usulan.rumpun_diklat', 'usulan.nama_diklat')
                ->where('pengiriman.nip', $nip)
                ->get(),
        ];

        // dd($data);

        return $data;
    }
}
