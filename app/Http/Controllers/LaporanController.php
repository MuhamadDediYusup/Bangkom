<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Pengiriman;
use GuzzleHttp\Psr7\Utils;
use App\Models\UsulanModel;
use App\Models\LaporanModel;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Models\Pengiriman as PengirimanModel;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class LaporanController extends Controller
{

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

        try {
            $data = [
                'title' => 'Daftar Laporan Pengembangan Kompetensi'
            ];

            return view('laporan.index', $data);
        } catch (\Throwable $th) {
            return redirect()->route('laporan.index')->with('error', 'Kesalahan sistem, data tidak ditemukan');
        }
    }

    public function form_laporan(Request $request)
    {
        try {
            $data = [
                'title' => 'Form Laporan Pengembangan Kompetensi',
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
            return view('laporan.form_laporan', $data);
        } catch (\Throwable $th) {
            return redirect()
                ->route('laporan.form_laporan')
                ->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function create($nip, $id_usul)
    {

        try {
            if ($id_usul !== null && $id_usul !== '' && $id_usul !== '000') {

                $usulan = PengirimanModel::join('pegawai', 'pengiriman.nip', '=', 'pegawai.nip')
                    ->join('usulan', 'usulan.id_usul', '=', 'pengiriman.id_usul')
                    ->where('pengiriman.id_usul', $id_usul)->first();

                if (empty($usulan)) {
                    return redirect()->route('laporan.index')->with('error', 'Data Pegawai tidak ditemukan');
                }

                $data = [
                    'title' => 'Laporan Pengembangan Kompetensi',
                    'data' => $usulan,
                    'jenis_diklat' => DB::table('diklat')
                        ->select('jenis_diklat')
                        ->distinct()
                        ->get(),
                    'usul' => true
                ];
            } else {

                $pegawai = PegawaiModel::where('nip', $nip)->first();

                if (empty($pegawai)) {
                    return redirect()->route('laporan.form_laporan')->with('error', 'Data Pegawai tidak ditemukan');
                }

                $data = [
                    'title' => 'Laporan Pengembangan Kompetensi',
                    'data' => $pegawai,
                    'jenis_diklat' => DB::table('diklat')
                        ->select('jenis_diklat')
                        ->distinct()
                        ->get(),
                ];
            }

            return view('laporan.create', $data);
        } catch (\Throwable $th) {
            return redirect()->route('laporan.index')->with('error', 'Kesalahan sistem, data tidak ditemukan');
        }
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'nip' => 'required',
            'nama' => 'required',
            'jenis_diklat' => 'required',
            'sub_jenis_diklat' => 'required',
            'rumpun_diklat' => 'required',
            'nama_diklat' => 'required',
            'tempat_diklat' => 'required',
            'penyelenggara_diklat' => 'required',
            'lama_pendidikan' => 'required',
            'tgl_sttpp' => 'required|date|before_or_equal:today',
            'file_sttpp' => 'required|file|mimes:pdf|max:2048',
            'file_surat_laporan' => 'file|mimes:pdf|max:2048',
        ]);

        $cekLaporan = LaporanModel::where('nip', $request->nip)
            ->where('nama_diklat', $request->nama_diklat)
            ->firstOr(function () {
                return null;
            });

        if (is_countable($cekLaporan) && count($cekLaporan) >= 1) {
            return $this->update($request, $request->nip, $cekLaporan->id_lapor);
        }

        // XSS Filtering & Sanitizing Input Data
        $request->merge(array_map('strip_tags', $request->all()));
        $request->merge(array_map('trim', $request->all()));

        try {
            if (!empty($request->id_usul) && !empty($request->id_pengiriman)) {
                $input['id_usul'] = $request->id_usul;
                $input['id_pengiriman'] = $request->id_pengiriman;

                $pengiriman = PengirimanModel::findOrFail($request->id_pengiriman);
                $pengiriman->status = '1';
                $pengiriman->save();
            }

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
            $input['nomor_surat'] = $request->nomor_surat;
            $input['tgl_surat'] = $request->tgl_surat;
            $input['tgl_mulai'] = $request->tahun_mulai;
            $input['tgl_selesai'] = $request->tahun_selesai;
            $input['tahun_angkatan'] = $request->tahun_angkatan;
            $input['tgl_sttpp'] = $request->tgl_sttpp;
            $input['nomor_sttpp'] = $request->nomor_sttpp;
            $input['id_diklat'] = $diklat[1];
            $input['id_siasn'] = $diklat[2];
            $input['sertifikat_siasn'] = $diklat[3];
            $input['status'] = null;
            $input['entry_user'] = Auth::user()->user_id;
            $input['entry_time'] = Carbon::now();

            $uniq_id = Carbon::now()->timestamp;

            if ($request->hasFile('file_surat_laporan')) {
                $spt_ext = $request->file('file_surat_laporan')->getClientOriginalExtension();
                $file = $request->file('file_surat_laporan');
                $fileName = $request->nip . '_LAPORAN_' . $request->tgl_sttpp . '_' . $uniq_id . '.' . $spt_ext;
                $destinationPath = public_path() . '/Lamp_Laporan';
                $file->move($destinationPath, $fileName);
                $input['file_surat_laporan'] = $fileName;
            }

            if ($request->hasFile('file_sttpp')) {
                $sttpp_ext = $request->file('file_sttpp')->getClientOriginalExtension();
                $file = $request->file('file_sttpp');
                $fileName = $request->nip . '_STTPP_' . $request->tgl_sttpp . '_' . $uniq_id . '.' . $sttpp_ext;
                $destinationPath = public_path() . '/Lamp_Sertifikat';
                $file->move($destinationPath, $fileName);
                $input['file_sttpp'] = $fileName;
            }

            try {
                // LaporanModel::create($input);
                DB::table('laporan')->insert($input);
                return redirect()->route('laporan.index')
                    ->with('success', 'Berhasil menambahkan laporan');
            } catch (\Throwable $th) {
                return redirect()->route('laporan.index')
                    ->with('error', 'Kesalahan sistem, data tidak disimpan' . $th->getMessage());
            }
        } catch (\Throwable $th) {
            return redirect()->route('pengiriman')
                ->with('error', 'Kesalahan sistem, data tidak disimpan' . $th->getMessage());
        }
    }

    public function edit(Request $request, $nip, $id, $status)
    {

        // check for redirect url from kompetensiasn.index or laporan.index
        $url = explode('?', request()->headers->get('referer'));
        $url_referer = $url[0];

        if (isset($url[1])) {
            $url_referer_id = $url[1];
        } else {
            $url_referer_id = null;
        }

        if ($url_referer == route('kompetensiasn.index')) {
            $param = $url_referer_id;
        } else {
            $param = 'laporan.index';
        }

        if ($request->has('usul')) {
            $laporan = LaporanModel::join('usulan', 'laporan.id_usul', '=', 'usulan.id_usul')
                ->join('pengiriman', 'laporan.id_usul', '=', 'pengiriman.id_usul')
                ->join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
                ->select(
                    'laporan.*',
                    'usulan.*',
                    'pengiriman.*',
                    'laporan.file_surat_laporan as file_laporan',
                    'laporan.alasan as alasan_laporan',
                    'laporan.status as status_laporan',
                    'pegawai.nama_lengkap',
                    'pegawai.nip as nip_pegawai',
                )
                ->where('laporan.id_lapor', '=', $id)
                ->where('usulan.nip', '=', $nip)
                ->get()->first();
        } else {
            $laporan = LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
                ->where('laporan.nip', $nip)->where('laporan.id_lapor', $id)->select('laporan.*', 'pegawai.nama_lengkap', 'pegawai.nip as nip_pegawai')->first();
        }

        if (empty($laporan)) {
            return redirect()->route('laporan.index')
                ->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'title' => 'Perubahan Laporan Pengembangan Kompetensi 2022',
            'data' => $laporan,
            'status' => $status,
            'pegawai' => PegawaiModel::where('nip', $nip)->first(),
            'jenis_diklat' => DB::table('diklat')
                ->select('jenis_diklat')
                ->distinct()
                ->get(),
        ];

        return view('laporan.edit', $data, compact('param', 'url_referer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LaporanModel  $laporanModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nip, $id)
    {
        $this->validate($request, [
            'file_surat_laporan' => 'file|mimes:pdf|max:2048',
            'file_sttpp' => 'file|mimes:pdf|max:2048',
            // 'tahun_mulai' => 'required|date|before_or_equal:today',
            // 'tahun_selesai' => 'required|date|before_or_equal:today',
            'tgl_sttpp' => 'required|date|before_or_equal:today',
        ]);

        if ($request->id_usul !== null && $request->id_usul !== '') {

            $laporan = LaporanModel::find($id);

            if (auth()->user()->can('laporan-approve')) {
                $laporan->status = $request->status;
                $laporan->alasan = $request->alasan;
            } else if ($laporan->status == '2') {
                if ($request->status) {
                    $laporan->status = $request->status;
                } else {
                    $laporan->status = '3';
                }
            }

            $request->merge(array_map('strip_tags', $request->all()));
            $request->merge(array_map('trim', $request->all()));

            // $input = $request->all();
            $idUsul = $laporan->id_usul;
            $pengiriman = Pengiriman::where('id_usul', $idUsul)->first();
            $usulan = UsulanModel::where('id_usul', $idUsul)->first();

            // update to usulan
            $diklat = explode(',', $request->rumpun_diklat);
            $usulan->jenis_diklat = $request->jenis_diklat;
            $usulan->sub_jenis_diklat = $request->sub_jenis_diklat;
            $usulan->rumpun_diklat = $diklat[0];
            $usulan->id_diklat = $diklat[1];
            $usulan->id_siasn = $diklat[2];
            $usulan->sertifikat_siasn = $diklat[3];
            $usulan->nama_diklat = $request->nama_diklat;
            $usulan->update();

            // update to pengiriman
            $pengiriman->tempat_diklat = $request->tempat_diklat;
            $pengiriman->penyelenggara_diklat = $request->penyelenggara_diklat;
            $pengiriman->tgl_mulai = $request->tgl_mulai;
            $pengiriman->tgl_selesai = $request->tgl_selesai;
            $pengiriman->nomor_surat = $request->nomor_surat;
            $pengiriman->tgl_surat = $request->tgl_surat;
            $pengiriman->status = 1;
            $pengiriman->update();

            // save to laporan

            $diklat = explode(',', $request->rumpun_diklat);
            $laporan->nama = $request->nama;
            $laporan->nip = $nip;
            $laporan->jenis_diklat = $request->jenis_diklat;
            $laporan->sub_jenis_diklat = $request->sub_jenis_diklat;
            $laporan->rumpun_diklat = $diklat[0];
            $laporan->nama_diklat = $request->nama_diklat;
            $laporan->tempat_diklat = $request->tempat_diklat;
            $laporan->penyelenggara_diklat = $request->penyelenggara_diklat;
            $laporan->lama_pendidikan = $request->lama_pendidikan;
            $laporan->tgl_mulai = $request->tgl_mulai;
            $laporan->tgl_selesai = $request->tgl_selesai;
            $laporan->tahun_angkatan = $request->tahun_angkatan;
            $laporan->tgl_sttpp = $request->tgl_sttpp;
            $laporan->nomor_sttpp = $request->nomor_sttpp;
            $laporan->nomor_surat = $request->nomor_surat;
            $laporan->tgl_surat = $request->tgl_surat;
            $laporan->id_diklat = $diklat[1];
            $laporan->id_siasn = $diklat[2];
            $laporan->sertifikat_siasn = $diklat[3];

            // $laporan->edit_time = Carbon::now();

            $uniq_id = Carbon::now()->timestamp;
            if (is_null($request->file('file_surat_laporan'))) {
                $laporan->file_surat_laporan = $laporan->file_surat_laporan;
            } else {
                if (File::exists(public_path('Lamp_Laporan/' . $pengiriman->file_surat_laporan))) {
                    File::delete(public_path('Lamp_Laporan/' . $pengiriman->file_surat_laporan));
                }

                $laporan_ext = $request->file('file_surat_laporan')->getClientOriginalExtension();
                $file = $request->file('file_surat_laporan');
                $fileName = $nip . '_LAPORAN_' . $request->tgl_surat . $uniq_id . '.' . $laporan_ext;
                $destinationPath = public_path() . '/Lamp_Laporan';
                $file->move($destinationPath, $fileName);
                $laporan->file_surat_laporan = $fileName;
            }

            if (is_null($request->file('file_sttpp'))) {
                $laporan->file_sttpp = $laporan->file_sttpp;
            } else {
                if (File::exists(public_path('Lamp_Sertifikat/' . $pengiriman->file_sttpp))) {
                    File::delete(public_path('Lamp_Sertifikat/' . $pengiriman->file_sttpp));
                }

                $laporan_ext = $request->file('file_sttpp')->getClientOriginalExtension();
                $file = $request->file('file_sttpp');
                $fileName = $nip . '_STTPP_' . $request->tgl_surat . $uniq_id . '.' . $laporan_ext;
                $destinationPath = public_path() . '/Lamp_Sertifikat';
                $file->move($destinationPath, $fileName);
                $laporan->file_sttpp = $fileName;
            }

            $laporan->update();
        } else {
            $laporan = LaporanModel::find($id);

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
            $input['nomor_surat'] = $request->nomor_surat;
            $input['tgl_surat'] = $request->tgl_surat;
            $input['id_diklat'] = $diklat[1];
            $input['id_siasn'] = $diklat[2];
            $input['sertifikat_siasn'] = $diklat[3];
            // $laporan['edit_time'] = Carbon::now();

            if (auth()->user()->can('laporan-approve')) {
                $input['status'] = $request->status;
                $input['alasan'] = $request->alasan;
            } else if ($laporan->status == '2') {
                if ($request->status) {
                    $input['status'] = $request->status;
                } else {
                    $input['status'] = '3';
                }
            }

            $uniq_id = Carbon::now()->timestamp;

            $laporan = LaporanModel::where('id_lapor', $id)->where('nip', $nip)->first();

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

            LaporanModel::where('id_lapor', $id)->where('nip', $nip)->update($input);
        }

        // make edit_user
        DB::table('laporan')->where('id_lapor', $id)->where('nip', $nip)->update([
            'edit_user' => auth()->user()->user_id,
        ]);

        try {
            $tglMulai = new DateTime($request->tgl_mulai);
            $tglSelesai = new DateTime($request->tgl_selesai);
            $tglSttpp = new DateTime($request->tgl_sttpp);
            $diklat = explode(',', $request->rumpun_diklat);

            if ($request->jenis_diklat == "Manajerial") {
                $namaDiklatPost = $request->sub_jenis_diklat;
            } else {
                $namaDiklatPost = $request->nama_diklat;
            }

            if ($request->status == 1) {
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
                    "nama_diklat": "' . $namaDiklatPost . '",
                    "tempat_diklat": "' . $request->tempat_diklat . '",
                    "penyelenggara_diklat": "' . $request->penyelenggara_diklat . '",
                    "lama_pendidikan": "' . $request->lama_pendidikan . '",
                    "tahun_angkatan": "' . $request->tahun_angkatan . '",
                    "tahun_mulai": "' . $tglMulai->format('d-m-Y') . '",
                    "tahun_selesai": "' . $tglSelesai->format('d-m-Y') . '",
                    "nomor_sttpp": "' . $request->nomor_sttpp . '",
                    "tgl_sttpp": "' . $tglSttpp->format('d-m-Y') . '",
                    "user": "' . Auth::user()->user_id . '",
                    "id_siasn": "' . $diklat[2] . '",
                    "sertifikat_siasn": "' . $diklat[3] . '",
                    "url_file": "' . url('/') . '/Lamp_Sertifikat/' . $input['file_sttpp'] . '"
                }';

                $req = new GuzzleRequest("POST", "https://simpeg.slemankab.go.id/share/api_abangkomandan.php/$resource/$parameter/$token", $headers, $body);
                $res = $client->sendAsync($req)->wait();

                $responseContent = $res->getBody()->getContents();
                $response = json_decode($responseContent);

                if (isset($response->Status) && $response->Status === "Success|True") {
                    if ($request->referer_url == route('kompetensiasn.index')) {
                        return redirect()->route('kompetensiasn.index', $request->param_url)
                            ->with('success', $response->Message);
                    } else {
                        return redirect()->route('laporan.index')
                            ->with('success', $response->Message);
                    }
                } else {
                    if ($request->referer_url == route('kompetensiasn.index')) {
                        return redirect()->route('kompetensiasn.index', $request->param_url)
                            ->with('error', $response->Message);
                    } else {
                        return redirect()->route('laporan.index')
                            ->with('error', $response->Message);
                    }
                }
            }
        } catch (\Throwable $th) {
            if ($request->referer_url == route('kompetensiasn.index')) {
                return redirect()->route('kompetensiasn.index', $request->param_url)
                    ->with('error', 'Data diupdate, namun gagal posting ke SIMPEG dan SIASN ' . $th->getMessage());
            } else {
                return redirect()->route('laporan.index')
                    ->with('error', 'Data diupdate, namun gagal posting ke SIMPEG dan SIASN ' . $th->getMessage());
            }
        }


        if ($request->referer_url == route('kompetensiasn.index')) {
            return redirect()->route('kompetensiasn.index', $request->param_url)
                ->with('success', "Berhasil update laporan");
        } else {
            return redirect()->route('laporan.index')
                ->with('success', "Berhasil update laporan");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LaporanModel  $laporanModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $laporan = LaporanModel::find($id);

            if (!empty(PengirimanModel::where('id_usul', $laporan->id_usul)->first())) {
                if (PengirimanModel::where('id_usul', $laporan->id_usul)->first() == '1') {
                    PengirimanModel::where('id_usul', $laporan->id_usul)->update(['status' => '0']);
                }
            }

            if (File::exists(public_path('Lamp_Sertifikat/' . $laporan->file_sttpp || File::exists(public_path('Lamp_Laporan/' . $laporan->file_surat_laporan))))) {
                File::delete(public_path('Lamp_Sertifikat/' . $laporan->file_sttpp));
                File::delete(public_path('Lamp_Laporan/' . $laporan->file_surat_laporan));
            }
            $laporan->delete();
        } catch (\Throwable $th) {
            // return redirect()->route('laporan.index')
            //     ->with('error', 'Gagal menghapus laporan ');
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus laporan ' . $th->getMessage()
            ]);
        }

        $referer = url()->previous();
        $referer_url = explode('?', $referer)[0];
        $paramReferer = isset(explode('?', $referer)[1]) ? explode('?', $referer)[1] : '';

        if ($referer_url == route('kompetensiasn.index')) {
            return redirect()->route('kompetensiasn.index', $paramReferer)
                ->with('success', 'Berhasil menghapus laporan');
        } else {
            // return redirect()->route('laporan.index')
            //     ->with('success', 'Berhasil menghapus laporan');
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus laporan'
            ]);
        }
    }

    public function redirectAddLaporan()
    {
        return redirect()->route('pengiriman.index')
            ->with('info', "Untuk menambahkan Laporan, <br> Silahkan masuk pada Tab <b>Pengiriman</b> dan pilih Kolom <b>Laporan</b> lalu klik Icon  <i class='fa-solid fa-graduation-cap'></i></a>");
    }

    /* Filter */
    private function getFilterTanggal()
    {
        if (session()->has('filter_tanggal')) {
            if (session()->has('filter_tanggal') != '') {
                $filter_tanggal = explode(' s/d ', session()->get('filter_tanggal'));
                $start = Carbon::parse($filter_tanggal[0])->startOfMonth()->format('Y-m-d');
                $end = Carbon::parse($filter_tanggal[1])->endOfMonth()->format('Y-m-d');
            } else {
                $start = '0000-00-00';
                $end = date('Y-m-d');
            }
        } else {
            $start = '0000-00-00';
            $end = date('Y-m-d');
        }

        return [$start, $end];
    }

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

    /* Row Count */
    function getRowLaporanDitinjau()
    {

        $filter_tanggal = $this->getFilterTanggal();
        $filter_nama_diklat = $this->getFilterNamaDiklat();
        $filter_nama_nip = $this->getFilterNamaNip();

        $sessionId = session()->get('id_perangkat_daerah');

        if (auth()->user()->can('list-all-perangkat-daerah')) {
            if ($sessionId == 'all') {
                $id_perangkat_daerah = '';
            } else {
                $id_perangkat_daerah = $sessionId;
            }
        } else {
            $id_perangkat_daerah = checkIdPerangkatDaerah();
        }

        $data = LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
            ->where(function ($query) {
                $query->where('status', '=', null)
                    ->orWhere('status', '=', '3');
            })
            ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
            ->whereBetween('tgl_sttpp', [$filter_tanggal[0], $filter_tanggal[1]])
            ->where(function ($query) use ($filter_nama_diklat, $filter_nama_nip) {
                $query->where('laporan.nama_diklat', 'LIKE', '%' . $filter_nama_diklat . '%')
                    ->where(function ($query) use ($filter_nama_nip) {
                        $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filter_nama_nip . '%')
                            ->orWhere('pegawai.nip', 'LIKE', '%' . $filter_nama_nip . '%');
                    });
            })
            ->orderBy('status', 'desc')
            ->count();
        return $data;
    }

    function getRowLaporanDisetujui()
    {
        $filter_tanggal = $this->getFilterTanggal();
        $filter_nama_diklat = $this->getFilterNamaDiklat();
        $filter_nama_nip = $this->getFilterNamaNip();

        $sessionId = session()->get('id_perangkat_daerah');

        if (auth()->user()->can('list-all-perangkat-daerah')) {
            if ($sessionId == 'all') {
                $id_perangkat_daerah = '';
            } else {
                $id_perangkat_daerah = $sessionId;
            }
        } else {
            $id_perangkat_daerah = checkIdPerangkatDaerah();
        }

        $data = LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
            ->where('status', '=', '1')
            ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
            ->whereBetween('tgl_sttpp', [$filter_tanggal[0], $filter_tanggal[1]])
            ->where(function ($query) use ($filter_nama_diklat, $filter_nama_nip) {
                $query->where('laporan.nama_diklat', 'LIKE', '%' . $filter_nama_diklat . '%')
                    ->where(function ($query) use ($filter_nama_nip) {
                        $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filter_nama_nip . '%')
                            ->orWhere('pegawai.nip', 'LIKE', '%' . $filter_nama_nip . '%');
                    });
            })
            ->orderBy('status', 'desc')
            ->count();

        return $data;
    }

    function getRowLaporanDiperbaiki()
    {
        $filter_tanggal = $this->getFilterTanggal();
        $filter_nama_diklat = $this->getFilterNamaDiklat();
        $filter_nama_nip = $this->getFilterNamaNip();

        $sessionId = session()->get('id_perangkat_daerah');

        if (auth()->user()->can('list-all-perangkat-daerah')) {
            if ($sessionId == 'all') {
                $id_perangkat_daerah = '';
            } else {
                $id_perangkat_daerah = $sessionId;
            }
        } else {
            $id_perangkat_daerah = checkIdPerangkatDaerah();
        }

        $data = LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
            ->where('status', '=', '2')
            ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
            ->whereBetween('tgl_sttpp', [$filter_tanggal[0], $filter_tanggal[1]])
            ->where(function ($query) use ($filter_nama_diklat, $filter_nama_nip) {
                $query->where('laporan.nama_diklat', 'LIKE', '%' . $filter_nama_diklat . '%')
                    ->where(function ($query) use ($filter_nama_nip) {
                        $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filter_nama_nip . '%')
                            ->orWhere('pegawai.nip', 'LIKE', '%' . $filter_nama_nip . '%');
                    });
            })
            ->orderBy('status', 'desc')
            ->count();

        return $data;
    }

    function getRowLaporanDitolak()
    {
        $filter_tanggal = $this->getFilterTanggal();
        $filter_nama_diklat = $this->getFilterNamaDiklat();
        $filter_nama_nip = $this->getFilterNamaNip();

        $sessionId = session()->get('id_perangkat_daerah');

        if (auth()->user()->can('list-all-perangkat-daerah')) {
            if ($sessionId == 'all') {
                $id_perangkat_daerah = '';
            } else {
                $id_perangkat_daerah = $sessionId;
            }
        } else {
            $id_perangkat_daerah = checkIdPerangkatDaerah();
        }

        $data = LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
            ->where('status', '=', '0')
            ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
            ->whereBetween('tgl_sttpp', [$filter_tanggal[0], $filter_tanggal[1]])
            ->where(function ($query) use ($filter_nama_diklat, $filter_nama_nip) {
                $query->where('laporan.nama_diklat', 'LIKE', '%' . $filter_nama_diklat . '%')
                    ->where(function ($query) use ($filter_nama_nip) {
                        $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filter_nama_nip . '%')
                            ->orWhere('pegawai.nip', 'LIKE', '%' . $filter_nama_nip . '%');
                    });
            })
            ->orderBy('status', 'desc')
            ->count();
        return $data;
    }
    /* End Row Cound */

    public function dataLaporanDitinjau($start, $end)
    {
        $filter_tanggal = $this->getFilterTanggal();
        $filter_nama_diklat = $this->getFilterNamaDiklat();
        $filter_nama_nip = $this->getFilterNamaNip();

        if (auth()->user()->can('list-all-perangkat-daerah')) {
            $orderBy = "asc";
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

        $data = [
            'ditinjau' => LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
                ->select(
                    'pegawai.nip',
                    'pegawai.perangkat_daerah',
                    'pegawai.nama_lengkap',
                    'laporan.jenis_diklat',
                    'laporan.sub_jenis_diklat',
                    'laporan.nama_diklat',
                    'laporan.lama_pendidikan',
                    'laporan.tgl_sttpp',
                    'laporan.entry_time',
                    'laporan.edit_time',
                    'laporan.id_lapor',
                    'laporan.status'
                )
                ->where(function ($query) {
                    $query->where('status', '=', null)
                        ->orWhere('status', '=', '3');
                })
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->where(function ($query) use ($filter_tanggal) {
                    if ($filter_tanggal) {
                        $query->whereBetween('tgl_sttpp', [$filter_tanggal[0], $filter_tanggal[1]]);
                    }
                })
                ->where(function ($query) use ($filter_nama_diklat, $filter_nama_nip) {
                    $query->where('laporan.nama_diklat', 'LIKE', '%' . $filter_nama_diklat . '%')
                        ->where(function ($query) use ($filter_nama_nip) {
                            $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filter_nama_nip . '%')
                                ->orWhere('pegawai.nip', 'LIKE', '%' . $filter_nama_nip . '%');
                        });
                })
                ->orderBy('status', 'desc')
                ->orderBy('laporan.id_lapor', $orderBy)
                ->skip($start)
                ->take($end)
                ->get(),
            'row_count' => $this->getRowLaporanDitinjau(),
        ];

        return $data;
    }

    public function dataLaporanDisetujui($start, $end)
    {
        $filter_tanggal = $this->getFilterTanggal();
        $filter_nama_diklat = $this->getFilterNamaDiklat();
        $filter_nama_nip = $this->getFilterNamaNip();

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

        $data = [
            'disetujui' => LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
                ->select(
                    'pegawai.nip',
                    'pegawai.perangkat_daerah',
                    'pegawai.nama_lengkap',
                    'laporan.jenis_diklat',
                    'laporan.sub_jenis_diklat',
                    'laporan.nama_diklat',
                    'laporan.lama_pendidikan',
                    'laporan.tgl_sttpp',
                    'laporan.entry_time',
                    'laporan.edit_time',
                    'laporan.id_lapor',
                    'laporan.status',
                    'laporan.alasan'
                )
                ->where('status', '=', '1')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->whereBetween('tgl_sttpp', [$filter_tanggal[0], $filter_tanggal[1]])
                ->where(function ($query) use ($filter_nama_diklat, $filter_nama_nip) {
                    $query->where('laporan.nama_diklat', 'LIKE', '%' . $filter_nama_diklat . '%')
                        ->where(function ($query) use ($filter_nama_nip) {
                            $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filter_nama_nip . '%')
                                ->orWhere('pegawai.nip', 'LIKE', '%' . $filter_nama_nip . '%');
                        });
                })
                ->orderBy('status', 'desc')
                ->orderBy('laporan.id_lapor', $orderBy)
                ->skip($start)
                ->take($end)
                ->get(),
            'row_count' =>  $this->getRowLaporanDisetujui(),
        ];

        return $data;
    }

    public function dataLaporanDiperbaiki($start, $end)
    {
        $filter_tanggal = $this->getFilterTanggal();
        $filter_nama_diklat = $this->getFilterNamaDiklat();
        $filter_nama_nip = $this->getFilterNamaNip();

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

        $data = [
            'diperbaiki' => LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
                ->select(
                    'pegawai.nip',
                    'pegawai.perangkat_daerah',
                    'pegawai.nama_lengkap',
                    'laporan.jenis_diklat',
                    'laporan.sub_jenis_diklat',
                    'laporan.nama_diklat',
                    'laporan.lama_pendidikan',
                    'laporan.tgl_sttpp',
                    'laporan.entry_time',
                    'laporan.edit_time',
                    'laporan.id_lapor',
                    'laporan.status',
                    'laporan.alasan'
                )
                ->where('status', '=', '2')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->whereBetween('tgl_sttpp', [$filter_tanggal[0], $filter_tanggal[1]])
                ->where(function ($query) use ($filter_nama_diklat, $filter_nama_nip) {
                    $query->where('laporan.nama_diklat', 'LIKE', '%' . $filter_nama_diklat . '%')
                        ->where(function ($query) use ($filter_nama_nip) {
                            $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filter_nama_nip . '%')
                                ->orWhere('pegawai.nip', 'LIKE', '%' . $filter_nama_nip . '%');
                        });
                })
                ->orderBy('status', 'desc')
                ->orderBy('laporan.id_lapor', $orderBy)
                ->skip($start)
                ->take($end)
                ->get(),
            'row_count' =>  $this->getRowLaporanDiperbaiki(),

        ];

        return $data;
    }

    public function dataLaporanDitolak($start, $end)
    {
        $filter_tanggal = $this->getFilterTanggal();
        $filter_nama_diklat = $this->getFilterNamaDiklat();
        $filter_nama_nip = $this->getFilterNamaNip();

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

        $data = [
            'ditolak' => LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
                ->select(
                    'pegawai.nip',
                    'pegawai.perangkat_daerah',
                    'pegawai.nama_lengkap',
                    'laporan.jenis_diklat',
                    'laporan.sub_jenis_diklat',
                    'laporan.nama_diklat',
                    'laporan.lama_pendidikan',
                    'laporan.tgl_sttpp',
                    'laporan.entry_time',
                    'laporan.edit_time',
                    'laporan.id_lapor',
                    'laporan.status',
                    'laporan.alasan'
                )
                ->where('status', '=', '0')
                ->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')
                ->whereBetween('tgl_sttpp', [$filter_tanggal[0], $filter_tanggal[1]])
                ->where(function ($query) use ($filter_nama_diklat, $filter_nama_nip) {
                    $query->where('laporan.nama_diklat', 'LIKE', '%' . $filter_nama_diklat . '%')
                        ->where(function ($query) use ($filter_nama_nip) {
                            $query->where('pegawai.nama_lengkap', 'LIKE', '%' . $filter_nama_nip . '%')
                                ->orWhere('pegawai.nip', 'LIKE', '%' . $filter_nama_nip . '%');
                        });
                })
                ->orderBy('status', 'desc')
                ->orderBy('laporan.id_lapor', $orderBy)
                ->skip($start)
                ->take($end)
                ->get(),
            'row_count' => $this->getRowLaporanDitolak(),
        ];

        return $data;
    }

    public function export($id)
    {
        return Excel::download(new LaporanExport($id), 'Laporan_Bangkom.xlsx');
    }
}
