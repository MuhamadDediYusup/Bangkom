<?php

namespace App\Exports;

use App\Models\Pengiriman;
use App\Models\UsulanModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class PengirimanExport implements FromCollection
{

    public $id = null;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $where = "pegawai.id_perangkat_daerah LIKE '" . $this->id . "%'";

        $qry_status = DB::raw("CASE WHEN pengiriman.status = '0' THEN 'Ditolak' ELSE 'Ditinjau' END AS status");
        $qry_nip = DB::raw("CONCAT('\'', pegawai.nip) AS nip");

        $pengirimanQuery = Pengiriman::join('pegawai', 'pegawai.nip', '=', 'pengiriman.nip')
            ->join('usulan', 'usulan.id_usul', '=', 'pengiriman.id_usul')
            ->select($qry_nip, 'pegawai.nama_lengkap', 'usulan.jenis_diklat', 'usulan.sub_jenis_diklat', 'usulan.rumpun_diklat', 'usulan.nama_diklat', 'pengiriman.tempat_diklat', 'pengiriman.penyelenggara_diklat', 'pengiriman.nomor_surat', 'pengiriman.tgl_surat', 'pengiriman.tgl_mulai', 'pengiriman.tgl_selesai', $qry_status)
            ->whereRaw($where)
            ->get()->toArray();

        // Excel Header
        $pengiriman = array();
        $pengiriman[] = array('#', 'NIP', 'Nama Lengkap', 'Jenis Diklat', 'Sub Jenis Diklat', 'Rumpun Diklat', 'Nama Diklat', 'Tempat Diklat', 'Penyelengaara Diklat', 'Nomor Surat', 'Tanggal Surat', 'Tanggal Mulai', 'Tanggal Selesai', 'Status');

        // Excel Body
        $i = 1;
        foreach ($pengirimanQuery as $key => $value) {

            $pengiriman[] = array(
                $i,
                $value['nip'],
                $value['nama_lengkap'],
                $value['jenis_diklat'],
                $value['sub_jenis_diklat'],
                $value['rumpun_diklat'],
                $value['nama_diklat'],
                $value['tempat_diklat'],
                $value['penyelenggara_diklat'],
                $value['nomor_surat'],
                $value['tgl_surat'],
                $value['tgl_mulai'],
                $value['tgl_selesai'],
                $value['status']
            );
            $i++;
        }

        return collect($pengiriman);
    }
}
