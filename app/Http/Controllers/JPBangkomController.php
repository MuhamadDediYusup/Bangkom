<?php

namespace App\Http\Controllers;

use App\Exports\JPBangkomExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JPBangkomController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'title' => "20 JP Pengembangan Kompetensi (Tahun " . Carbon::now()->year . ")",
        ];

        try {
            if ($request->has('id_perangkat_daerah') && $request->has('jam')) {

                if (auth()->user()->can('search-option-disabled')) {
                    $request->id_perangkat_daerah = checkIdPerangkatDaerah();
                } else {
                    $request->id_perangkat_daerah = $request->id_perangkat_daerah;
                }

                try {
                    $data['jp'] = putData('20JP', $request->id_perangkat_daerah . '+' . $request->jam)->Data;
                } catch (\Throwable $th) {
                    $data['jp'] = [];
                }
                $data['id_perangkat_daerah'] = substr($request->id_perangkat_daerah, 0, 2);
                $data['jam'] = $request->jam;
            }

            return view('jp_bangkom.index', $data);
        } catch (\Throwable $th) {
            return redirect()->route('jp_bangkom.index')->with('error', 'Data tidak ditemukan');
        }
    }

    public function export($pd, $jam)
    {
        $date = date('dmY');
        return Excel::download(new JPBangkomExport($pd, $jam), '20JP_Bangkom_' . $date . '.xlsx');
    }
}
