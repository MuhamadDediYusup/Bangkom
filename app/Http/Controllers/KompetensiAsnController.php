<?php

namespace App\Http\Controllers;

use UsulBangkom;
use GuzzleHttp\Client;
use App\Models\UsulanModel;
use App\Models\LaporanModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Support\Facades\Auth;

class KompetensiAsnController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = [
                'title' => 'Kompetensi ASN',
            ];

            if (auth()->user()->can('search-option-disabled')) {
                $request->id_perangkat_daerah = checkIdPerangkatDaerah();
            } else {
                $request->id_perangkat_daerah = $request->id_perangkat_daerah;
            }

            if (auth()->user()->can('personal-locked')) {
                $request->nama_nip = Auth::user()->user_id;
                $data['id_perangkat_daerah'] = checkIdPerangkatDaerah();
                $data['nama_nip'] = Auth::user()->user_id;
            } else {
                $request->nama_nip = $request->nama_nip;
            }

            // dd($request->id_perangkat_daerah, $request->nama_nip);

            if ($request->has('id_perangkat_daerah')) {
                $listPeg = putData('list_peg', $request->id_perangkat_daerah . '+' . $request->nama_nip);
                if ($listPeg->Status == 'Success|True') {
                    if ((count($listPeg->Data) > 0) && (count($listPeg->Data) < 2)) {
                        $nip = $listPeg->Data[0]->nip;
                        $data['identitas'] = putData('ident_peg', $nip)->Data;
                        $data['kompetensi'] = putData('personal', $nip)->Data;
                        $data['jamPelajaran'] = putData('20JP', '+nip+' . $nip)->Data;
                        $data['usulan'] = DB::table('usulan')
                            ->join('pengiriman', 'usulan.id_usul', '=', 'pengiriman.id_usul', 'left outer')
                            ->select(DB::raw('if(pengiriman.status, 9, usulan.status) as status_usulan'), 'usulan.*')
                            ->where('usulan.nip', $nip)
                            ->get();
                        $data['pengiriman'] = DB::table('pengiriman')
                            ->join('usulan', 'pengiriman.id_usul', '=', 'usulan.id_usul')
                            ->select('pengiriman.*', 'usulan.jenis_diklat', 'usulan.sub_jenis_diklat', 'usulan.rumpun_diklat', 'usulan.nama_diklat')
                            ->where('pengiriman.nip', $nip)
                            ->get();
                        $data['laporan'] = DB::table('laporan')
                            ->select('laporan.*')
                            ->where('laporan.nip', '=', $nip)
                            ->orderby('tgl_sttpp', 'desc')
                            ->get();
                        $data['id_perangkat_daerah'] = $request->id_perangkat_daerah;
                        $data['nama_nip'] = $request->nama_nip;
                    } else {
                        redirect()->route('kompetensiasn.index')->with('error', 'Data pegawai tidak ditemukan atau lebih dari satu orang');
                    }
                } else {
                    redirect()->route('kompetensiasn.index')->with('error', 'Data pegawai tidak ditemukan atau lebih dari satu orang');
                }
            }

            return view('kompetensi_asn.index', $data);
        } catch (\Throwable $th) {
            return redirect()->route('kompetensiasn.index')->with('error', 'Data pegawai tidak ditemukan atau lebih dari satu orang');
        }
    }

    public function edit($jenis_diklat, $nip, $id_diklat)
    {
        try {
            $nip = $nip;
            $id_diklat = $id_diklat;
            $jenis_diklat = $jenis_diklat;
            $data_diklat = putData('view', "$jenis_diklat+$nip+$id_diklat");

            // dd($data_diklat);

            if (isset($data_diklat->Data)) {
                $data = [
                    'title' => 'Edit Kompetensi ASN',
                    'diklat' => $data_diklat->Data[0],
                    'jenis_diklat' => $jenis_diklat,
                    'master_diklat' => getData('master_diklat', 1),
                ];

                // dd($data);

                return view('kompetensi_asn.edit', $data);
            } else {
                return redirect()->route('kompetensiasn.index')->with('error', 'Data Diklat tidak ditemukan');
            }
        } catch (\Throwable $th) {
            return redirect()->route('kompetensiasn.index')->with('error', 'Data Diklat tidak ditemukan');
        }
    }

    public function update(Request $request)
    {

        $diklat = explode(',', $request->sub_jenis_diklat);
        $sub_jenis_diklat = $diklat[0];
        $id_diklat = $diklat[1];
        $id_siasn = $diklat[2];
        $sertifikat_siasn = $diklat[3];

        if ($request->jenis_diklat == 'Manajerial') {
            $namaDiklat = $sub_jenis_diklat;
        } else {
            $namaDiklat = $request->nama_diklat;
        }


        try {
            $tglMulai = new DateTime($request->tahun_mulai);
            $tglSelesai = new DateTime($request->tahun_selesai);
            $tglSttpp = new DateTime($request->tgl_sttpp);

            $nip = $request->nip;
            $id_pendidikan = $request->id_pegawai_pendidikan;
            $jenis_diklat = $request->jenis_diklat_pendidikan;

            $resource = 'update';
            $parameter = "$jenis_diklat+$nip+$id_pendidikan";

            $token = getToken($resource, $parameter);

            $client = new Client();
            $headers = [
                'Content-Type' => 'text/plain'
            ];
            $body = '{
                "id_diklat": "' . $id_diklat . '",
                "nama_diklat": "' . $namaDiklat . '",
                "tempat_diklat": "' . $request->tempat_diklat . '",
                "penyelenggara_diklat": "' . $request->penyelenggara . '",
                "lama_pendidikan": "' . $request->lama_pendidikan . '",
                "tahun_angkatan": "' . $request->tahun_angkatan . '",
                "tahun_mulai": "' . $tglMulai->format('d-m-Y') . '",
                "tahun_selesai": "' . $tglSelesai->format('d-m-Y') . '",
                "nomor_sttpp": "' . $request->no_sertifikat . '",
                "tgl_sttpp": "' . $tglSttpp->format('d-m-Y') . '",
                "user": "' . Auth::user()->user_id . '",
                "id_siasn": "' . $id_siasn . '",
                "sertifikast_siasn": "' . $sertifikat_siasn . '"
            }';

            $request = new GuzzleRequest("POST", "https://simpeg.slemankab.go.id/share/api_abangkomandan.php/$resource/$parameter/$token", $headers, $body);
            $res = $client->sendAsync($request)->wait();

            $response = json_decode($res->getBody()->getContents());

            if ($response->Status === "Success|True") {
                return redirect()->route('kompetensiasn.index', "id_perangkat_daerah=&nama_nip=$nip")->with('success', $response->Message);
            } else {
                return redirect()->route('kompetensiasn.index', "id_perangkat_daerah=&nama_nip=$nip")->with('error', $response->Message);
            }
        } catch (\Throwable $th) {
            return redirect()->route('kompetensiasn.index')->with('error', 'Data tidak berhasil diubah' . $th->getMessage());
        }
    }

    public function delete($nip, $idPendidikan, $jenidDiklat)
    {
        // dd($nip, $idPendidikan, $jenidDiklat);
        try {
            $delete = delData('delete', "$jenidDiklat+$nip+$idPendidikan");
        } catch (\Throwable $th) {
            return redirect()->route('kompetensiasn.index')->with('error', $delete->Message);
        }

        if ($delete->Status == 'Success|True') {
            return redirect()->route('kompetensiasn.index', "id_perangkat_daerah=&nama_nip=$nip")->with('success', $delete->Message);
        } else {
            return redirect()->route('kompetensiasn.index')->with('error', $delete->Message);
        }
    }

    public function dataSubJenisDiklat($jenis_diklat)
    {
        $data = DB::table('diklat')
            ->select('sub_jenis_diklat', 'id_diklat', 'id_siasn')
            ->where('jenis_diklat', $jenis_diklat)
            ->distinct()
            ->get();
        return $data;
    }
}
