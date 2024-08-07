<?php

namespace App\Http\Controllers;

use Pegawai;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use App\Models\LaporanModel_2022;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Psr7\Request as GuzzleRequest;


class LaporanController_2022 extends Controller
{
    public function index()
    {
        try {
            if (auth()->user()->can('list-all-perangkat-daerah')) {
                $ditinjau = LaporanModel_2022::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')->where('status', '=', null)->get();
                $disetujui = LaporanModel_2022::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')->where('status', '=', '1')->get();
                $ditolak = LaporanModel_2022::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')->where('status', '=', '2')->get();
            } else {
                $ditinjau = LaporanModel_2022::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')->where('pegawai.id_perangkat_daerah', 'LIKE', checkIdPerangkatDaerah() . '%')
                    ->where('laporan.status', '=', null)->get();
                $disetujui = LaporanModel_2022::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')->where('pegawai.id_perangkat_daerah', 'LIKE', checkIdPerangkatDaerah() . '%')
                    ->where('laporan.status', '=', '1')->get();
                $ditolak = LaporanModel_2022::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')->where('pegawai.id_perangkat_daerah', 'LIKE', checkIdPerangkatDaerah() . '%')
                    ->where('laporan.status', '=', '2')->get();
            }

            $data = [
                'title' => 'Daftar Laporan Pengembangan Kompetensi 2022',
                'ditinjau' => $ditinjau,
                'disetujui' => $disetujui,
                'ditolak' => $ditolak,
                'row_count' => [
                    'ditinjau' => count($ditinjau),
                    'disetujui' => count($disetujui),
                    'ditolak' => count($ditolak)
                ]
            ];

            return view('laporan2022.index', $data);
        } catch (\Throwable $th) {
            return redirect()->route('laporan')->with('error', 'Kesalahan sistem, data tidak ditemukan');
        }
    }

    public function form_laporan(Request $request)
    {
        try {
            $data = [
                'title' => 'Form Laporan Pengembangan Kompetensi 2022',
                'perangkat_daerah' => getData('master_perangkat_daerah', 1),
            ];

            if ($request->has('id_perangkat_daerah')) {
                // $data['usulan'] = putData('list_peg', $request->id_perangkat_daerah . '+' . $request->nama);
                $data['usulan'] = PegawaiModel::where('id_perangkat_daerah', 'like', $request->id_perangkat_daerah . '%')
                    ->Where('nama_lengkap', 'like', '%' . $request->nama . '%')
                    ->orWhere('nip', 'like', '%' . $request->nama . '%')
                    ->Where('id_perangkat_daerah', 'like', checkIdPerangkatDaerah() . '%')
                    ->get();

                if (empty($data['usulan'])) {
                    redirect()
                        ->route('laporan_2022.form_laporan')
                        ->with('error', 'Data Pegawai tidak ditemukan');
                }
            }
            return view('laporan2022.form_laporan', $data);
        } catch (\Throwable $th) {
            return redirect()
                ->route('laporan_2022.form_laporan')
                ->with('error', 'Terjadi kesalahan' . $th->getMessage());
        }
    }

    public function create($nip)
    {

        $pegawai = PegawaiModel::where('nip', $nip)->first();

        if (empty($pegawai)) {
            return redirect()->route('laporan_2022.form_laporan')->with('error', 'Data Pegawai tidak ditemukan');
        }

        $data = [
            'title' => 'Laporan Pengembangan Kompetensi 2022',
            'pegawai' => $pegawai,
            'jenis_diklat' => DB::table('diklat')
                ->select('jenis_diklat')
                ->distinct()
                ->get(),
        ];

        return view('laporan2022.create', $data);
    }

    public function store(Request $request)
    {
        // Validasi Input
        $this->validate($request, [
            'nip' => 'required',
            'nama' => 'required',
            'jenis_diklat' => 'required',
            'sub_jenis_diklat' => 'required',
            'rumpun_diklat' => 'required',
            'nama_diklat' => 'required',
            'tahun_mulai' => 'required',
            'tahun_selesai' => 'required',
            'tempat_diklat' => 'required',
            'penyelenggara_diklat' => 'required',
            'lama_pendidikan' => 'required',
            'tgl_sttpp' => 'required',
            'file_sttpp' => 'required|file|mimes:pdf|max:2048',
            'file_surat_laporan' => 'file|mimes:pdf|max:2048',
        ]);

        // XSS Filtering & Sanitizing Input Data
        $request->merge(array_map('strip_tags', $request->all()));
        $request->merge(array_map('trim', $request->all()));

        try {

            $diklat = explode(',', $request->rumpun_diklat);

            $nip = $request->nip;
            $input['nama'] = $request->nama;
            $input['nip'] = $nip;
            $input['jenis_diklat'] = $request->jenis_diklat;
            $input['sub_jenis_diklat'] = $request->sub_jenis_diklat;
            $input['rumpun_diklat'] = $diklat[0];
            $input['nama_diklat'] = $request->nama_diklat;
            $input['tempat_diklat'] = $request->tempat_diklat;
            $input['penyelenggara_diklat'] = $request->penyelenggara_diklat;
            $input['lama_pendidikan'] = $request->lama_pendidikan;
            $input['tgl_mulai'] = $request->tahun_mulai;
            $input['tgl_selesai'] = $request->tahun_selesai;
            $input['tahun_angkatan'] = $request->tahun_angkatan;
            $input['tgl_sttpp'] = $request->tgl_sttpp;
            $input['nomor_sttpp'] = $request->nomor_sttpp;
            $input['id_diklat'] = $diklat[1];
            $input['id_siasn'] = $diklat[2];
            $input['status'] = null;
            $uniq_id = Carbon::now()->timestamp;

            if ($request->hasFile('file_surat_laporan')) {
                $spt_ext = $request->file('file_surat_laporan')->getClientOriginalExtension();
                $file = $request->file('file_surat_laporan');
                $fileName = $nip . '_LAPORAN_' . $request->tgl_sttpp . '_' . $uniq_id . '.' . $spt_ext;
                $destinationPath = public_path() . '/Lamp_Laporan';
                $file->move($destinationPath, $fileName);
                $input['file_surat_laporan'] = $fileName;
            }

            if ($request->hasFile('file_sttpp')) {
                $sttpp_ext = $request->file('file_sttpp')->getClientOriginalExtension();
                $file = $request->file('file_sttpp');
                $fileName = $nip . '_STTPP_' . $request->tgl_sttpp . '_' . $uniq_id . '.' . $sttpp_ext;
                $destinationPath = public_path() . '/Lamp_Sertifikat';
                $file->move($destinationPath, $fileName);
                $input['file_sttpp'] = $fileName;
            }
            LaporanModel_2022::create($input);

            return redirect()->route('laporan_2022.index')
                ->with('success', 'Berhasil menambahkan laporan');
        } catch (\Throwable $th) {
            return redirect()->route('laporan_2022.index')
                ->with('error', 'Kesalahan sistem, data tidak disimpan');
        }
    }

    public function edit($nip, $id_lapor)
    {
        try {
            $data = [
                'title' => 'Perubahan Laporan Pengembangan Kompetensi 2022',
                'data' => LaporanModel_2022::where('nip', $nip)->where('id_lapor', $id_lapor)->first(),
                'pegawai' => PegawaiModel::where('nip', $nip)->first(),
                'jenis_diklat' => DB::table('diklat')
                    ->select('jenis_diklat')
                    ->distinct()
                    ->get(),

            ];
            return view('laporan2022.edit', $data);
        } catch (\Throwable $th) {
            return redirect()->route('laporan.data')
                ->with('error', 'Kesalahan sistem, data tidak diubah');
        }
    }

    public function update(Request $request, $nip, $id_lapor)
    {


        $this->validate($request, [
            'file_surat_laporan' => 'file|mimes:pdf|max:2048',
            'file_sttpp' => 'file|mimes:pdf|max:2048',
        ]);

        $diklat = explode(',', $request->rumpun_diklat);
        $input['nama'] = $request->nama;
        $input['nip'] = $nip;
        $input['jenis_diklat'] = $request->jenis_diklat;
        $input['sub_jenis_diklat'] = $request->sub_jenis_diklat;
        $input['rumpun_diklat'] = $diklat[0];
        $input['nama_diklat'] = $request->nama_diklat;
        $input['tempat_diklat'] = $request->tempat_diklat;
        $input['penyelenggara_diklat'] = $request->penyelenggara_diklat;
        $input['lama_pendidikan'] = $request->lama_pendidikan;
        $input['tgl_mulai'] = $request->tgl_mulai;
        $input['tgl_selesai'] = $request->tgl_selesai;
        $input['tahun_angkatan'] = $request->tahun_angkatan;
        $input['tgl_sttpp'] = $request->tgl_sttpp;
        $input['nomor_sttpp'] = $request->nomor_sttpp;
        $input['id_diklat'] = $diklat[1];
        $input['id_siasn'] = $diklat[2];
        $input['alasan'] = $request->alasan;
        $input['status'] = $request->status;
        $uniq_id = Carbon::now()->timestamp;

        $laporan = LaporanModel_2022::where('id_lapor', $id_lapor)->where('nip', $nip)->first();

        if (is_null($request->file('file_sttpp'))) {
            $input['file_sttpp'] = $laporan->file_sttpp;
        } else {
            if (File::exists(public_path('Lamp_Sertifikat/' . $laporan->file_sttpp))) {
                File::delete(public_path('Lamp_Sertifikat/' . $laporan->file_sttpp));
            }

            $sttpp_ext = $request->file('file_sttpp')->getClientOriginalExtension();
            $file = $request->file('file_sttpp');
            $fileName = $nip . '_STTPP_' . $request->tgl_sttpp . '_' . $uniq_id . '.' . $sttpp_ext;
            $destinationPath = public_path() . '/Lamp_Sertifikat';
            $file->move($destinationPath, $fileName);
            $input['file_sttpp'] = $fileName;
        }

        if (is_null($request->file('file_surat_laporan'))) {
            $input['file_surat_laporan'] = $laporan->file_surat_laporan;
        } else {
            if (File::exists(public_path('Lamp_Laporan/' . $laporan->file_surat_laporan))) {
                File::delete(public_path('Lamp_Laporan/' . $laporan->file_surat_laporan));
            }

            $laporan_ext = $request->file('file_surat_laporan')->getClientOriginalExtension();
            $file = $request->file('file_surat_laporan');
            $fileName = $nip . '_LAPORAN_' . $request->tgl_surat . $uniq_id . '.' . $laporan_ext;
            $destinationPath = public_path() . '/Lamp_Laporan';
            $file->move($destinationPath, $fileName);
            $input['file_surat_laporan'] = $fileName;
        }

        LaporanModel_2022::where('id_lapor', $id_lapor)->where('nip', $nip)->update($input);

        try {
            if ($request->status == 1) {
                $nip = $nip;
                $jenis_diklat = $request->jenis_diklat;

                $resource = 'posting';
                $parameter = "$jenis_diklat+$nip";
                $token = getToken($resource, $parameter);

                $client = new Client();
                $headers = [
                    'Content-Type' => 'text/plain'
                ];
                $body = '{
                    "id_diklat": "' . $diklat[1] . '",
                    "nama_diklat": "' . $request->nama_diklat . '",
                    "tempat_diklat": "' . $request->tempat_diklat . '",
                    "penyelenggara_diklat": "' . $request->penyelenggara_diklat . '",
                    "tahun_angkatan": "' . $request->tahun_angkatan . '",
                    "tahun_mulai": "' . date('d-m-Y', strtotime($request->tgl_mulai)) . '",
                    "tahun_selesai": "' . date('d-m-Y', strtotime($request->tgl_selesai)) . '",
                    "lama_pendidikan": "' . $request->lama_pendidikan . '",
                    "nomor_sttpp": "' . $request->nomor_surat . '",
                    "tgl_sttpp": "' . date('d-m-Y', strtotime($request->tgl_sttpp)) . '"
                  }';


                $request = new GuzzleRequest("POST", "https://simpeg.slemankab.go.id/share/api_abangkomandan.php/$resource/$parameter/$token", $headers, $body);
                $res = $client->sendAsync($request)->wait();

                return redirect()->route('laporan_2022.index')
                    ->with('success', 'Berhasil update laporan dan posting ke SIMPEG');
            }
        } catch (\Throwable $th) {
            return redirect()->route('laporan_2022.index')
                ->with('error', 'Data tidak terposting ke SIMPEG');
        }

        return redirect()->route('laporan_2022.index')
            ->with('success', 'Berhasil mengubah laporan');
    }

    public function destroy($id)
    {


        $laporan = LaporanModel_2022::find($id);
        if (File::exists(public_path('Lamp_Sertifikat/' . $laporan->file_sttpp || File::exists(public_path('Lamp_Laporan/' . $laporan->file_surat_laporan))))) {
            File::delete(public_path('Lamp_Sertifikat/' . $laporan->file_sttpp));
            File::delete(public_path('Lamp_Laporan/' . $laporan->file_surat_laporan));
        }
        $laporan->delete();

        return redirect()->route('laporan_2022.index')
            ->with('success', 'Berhasil menghapus laporan');
    }
}
