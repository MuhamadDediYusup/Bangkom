<?php

namespace App\Http\Controllers;

use App\Exports\KompetensiExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Input\Input;

class KompetensiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = [
                'title' => 'Daftar Kompetensi',
                'perangkat_daerah' => getData('master_perangkat_daerah', 1),
            ];

            if ($request->has('id_perangkat_daerah')) {

                if (auth()->user()->can('search-option-disabled')) {
                    $request->id_perangkat_daerah = checkIdPerangkatDaerah();
                } else {
                    $request->id_perangkat_daerah = $request->id_perangkat_daerah;
                }

                // session
                if ($request->has('filter_nama_diklat')) {
                    session(['filter_nama_diklat' => $request->filter_nama_diklat]);
                }

                if ($request->has('filter_tanggal')) {
                    session(['filter_tanggal' => $request->filter_tanggal]);
                }

                if ($request->has('id_perangkat_daerah')) {
                    session(['id_perangkat_daerah' => $request->id_perangkat_daerah]);
                }

                $filter_nama_diklat = session('filter_nama_diklat');
                $filter_tanggal = session('filter_tanggal');
                // end session

                $kompetensi = putData('nominatif', $request->id_perangkat_daerah)->Data;

                // object to array
                $kompetensi = json_decode(json_encode($kompetensi), true);
                $kompetensi_new = json_decode(json_encode($kompetensi), true);

                foreach ($kompetensi as $key => $value) {
                    if ($key > 0) {
                        if ($kompetensi[$key]['nip'] == $kompetensi_new[$key - 1]['nip']) {
                            $kompetensi[$key]['nip'] = '';
                            $kompetensi[$key]['nama_lengkap'] = '';
                            $kompetensi[$key]['jabatan'] = '';
                        } else {
                            $kompetensi[$key]['nip'] = $kompetensi[$key]['nip'];
                            $kompetensi[$key]['nama_lengkap'] = $kompetensi[$key]['nama_lengkap'];
                        }
                    }
                }

                // array to object
                $kompetensi = json_decode(json_encode($kompetensi));

                $data['kompetensi'] = $kompetensi;
                $data['id_perangkat_daerah'] = $request->id_perangkat_daerah;

                if ($request->has('nip')) {
                    $nip = $request->nip;
                    $identitas = getData('ident_peg', $nip)->Data;
                    $kompetensi = putData('personal', $nip)->Data;
                    $jamPelajaran = getData('20JP', '+nip+' . $nip)->Data;

                    redirect()->route('kompetensi.index')->with('identitas', $identitas)->with('kompetensi', $kompetensi)->with('jamPelajaran', $jamPelajaran);
                }
            }

            return view('kompetensi.index', $data);
        } catch (\Throwable $th) {
            return redirect()->route('kompetensi.index')->with('error', $th->getMessage());
        }
    }

    public function export($id)
    {
        return Excel::download(new KompetensiExport($id), 'Kompetensi_Bangkom.xlsx');
    }
}
