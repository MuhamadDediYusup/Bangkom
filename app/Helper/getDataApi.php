<?php

use App\Models\User;
use GuzzleHttp\Client;
use App\Models\UsulanModel;
use App\Models\LaporanModel;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Carbon;
use Composer\CaBundle\CaBundle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// function get token
function getToken($resource, $parameter)
{
    $token = md5(sha1(strrev($resource) . strrev($parameter) . date("Ymd", time()) . 'JQ'));
    return $token;
}

function getData($resource, $parameter)
{
    try {
        $token = getToken($resource, $parameter);
        $client = new Client();
        $request = new Request('GET', "https://simpeg.slemankab.go.id/share/api_abangkomandan.php/$resource/$parameter/$token");
        $res = $client->sendAsync($request)->wait();
        $data = json_decode($res->getBody()->getContents());

        if ($data->Status == 'Rejected|Denied') {
            echo "<script>alert('Kesalahan Pada Server');window.location.href='/';</script>";
        } else {
            return json_decode($res->getBody());
        }
    } catch (\Throwable $th) {
        return $th;
    }
}

function putData($resource, $parameter)
{
    try {
        $token = getToken($resource, $parameter);

        $client = new Client();
        $request = new Request('PUT', "https://simpeg.slemankab.go.id/share/api_abangkomandan.php/$resource/$parameter/$token");
        $res = $client->sendAsync($request)->wait();
        return json_decode($res->getBody());
    } catch (\Throwable $th) {
        return $th;
    }
}

function delData($resource, $parameter)
{
    try {
        $token = getToken($resource, $parameter);

        $client = new Client();
        $body = '';
        $request = new Request('DELETE', "https://simpeg.slemankab.go.id/share/api_abangkomandan.php/$resource/$parameter/$token", [], $body);
        $res = $client->sendAsync($request)->wait();
        return json_decode($res->getBody());
    } catch (\Throwable $th) {
        throw $th;
    }
}

function getPerangkatDaerah()
{
    try {
        $resource = 'master_perangkat_daerah';
        $parameter = '1';

        $client = new Client();

        $token = getToken($resource, $parameter);
        $request = new Request('GET', "https://simpeg.slemankab.go.id/share/api_abangkomandan.php/$resource/$parameter/$token");
        $res = $client->sendAsync($request)->wait();
        return json_decode($res->getBody());
    } catch (\Throwable $th) {
        throw $th;
    }
}

function checkIdPerangkatDaerah()
{
    try {
        $nip = auth()->user()->user_id;

        $userIdPerangkatDaerah = User::join('pegawai', 'pegawai.nip', '=', 'users.user_id')
            ->select('users.id_perangkat_daerah')
            ->where('user_id', $nip)
            ->first();

        if ($userIdPerangkatDaerah == null) {
            throw new \Exception('Maaf.. Anda sudah tidak terdaftar sebagai pegawai atau hubungi admin untuk mengaktifkan akun Anda.', 403);
        }

        if (!empty($userIdPerangkatDaerah->id_perangkat_daerah)) {
            return $userIdPerangkatDaerah->id_perangkat_daerah;
        } else {
            $idPerangkatDaerah = getIdPerangkatDaerah($nip);
            return substr($idPerangkatDaerah, 0, 2);
        }
    } catch (\Throwable $th) {
        throw $th;
    }
}

function getIdPerangkatDaerah($nip)
{
    try {
        $idPerangkatDaerah = DB::table('pegawai')->select('id_perangkat_daerah')->where('nip', $nip)->first();
        return $idPerangkatDaerah->id_perangkat_daerah;
    } catch (\Throwable $th) {
        throw $th;
    }
}

function getIdPerangkatDaerahTwoDigit()
{
    return substr(checkIdPerangkatDaerah(), 0, 2);
}

function getCountDataLaporan()
{

    if (auth()->user()->can('list-all-perangkat-daerah')) {
        $id_perangkat_daerah = '';
    } else {
        $id_perangkat_daerah = checkIdPerangkatDaerah();
    }

    $laporanDitinjau = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('laporan.status', NULL)->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')->count();
    $laporanDiperbaiki = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('laporan.status', '2')->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')->count();
    $laporanDitolak = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('laporan.status', '0')->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')->count();
    $laporanDisetujui = LaporanModel::join('pegawai', 'pegawai.nip', '=', 'laporan.nip')->where('laporan.status', '1')->where('pegawai.id_perangkat_daerah', 'LIKE', $id_perangkat_daerah . '%')->count();

    return compact('laporanDitinjau', 'laporanDitolak', 'laporanDisetujui', 'laporanDiperbaiki');
}
