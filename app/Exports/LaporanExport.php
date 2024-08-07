<?php

namespace App\Exports;

use App\Models\LaporanModel;
use Maatwebsite\Excel\Sheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanExport implements FromCollection
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

        if ($this->id == 'all') {
            $where = 'pegawai.id_perangkat_daerah IS NOT NULL';
        } else {
            $where = "pegawai.id_perangkat_daerah LIKE '" . $this->id . "%'";
        }

        $qry_status = DB::raw("CASE WHEN laporan.status = '0' THEN 'Ditolak' WHEN laporan.status = '1' THEN 'Disetujui' ELSE 'Ditinjau' END AS status");
        $qry_nip = DB::raw("CONCAT('\'', pegawai.nip) AS nip");

        $laporanQuery = LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
            ->select($qry_nip, 'pegawai.nama_lengkap', 'laporan.jenis_diklat', 'laporan.sub_jenis_diklat', 'laporan.rumpun_diklat', 'laporan.nama_diklat', 'laporan.tempat_diklat', 'laporan.penyelenggara_diklat', 'laporan.tgl_mulai', 'laporan.tgl_selesai', 'laporan.nomor_surat', 'laporan.tgl_surat', 'laporan.lama_pendidikan', 'laporan.tahun_angkatan', 'laporan.nomor_sttpp', 'laporan.tgl_sttpp', $qry_status, 'laporan.alasan')
            ->whereRaw($where)
            ->get()->toArray();

        // Excel Header
        $laporan = array();
        $laporan[] = array('#', 'NIP', 'Nama Lengkap', 'Jenis Diklat', 'Sub Jenis Diklat', 'Rumpun Diklat', 'Nama Diklat', 'Tempat Diklat', 'Penyelenggara Diklat', 'Tanggal Mulai', 'Tanggal Selesai', 'Nomor Surat', 'Tanggal Surat', 'Lama Pendidikan', 'Tahun Angkatan', 'Nomor STTPP', 'Tanggal STTPP', 'Status', 'Alasan');

        // Excel Body
        $i = 1;
        foreach ($laporanQuery as $key => $value) {
            $laporan[] = array(
                $i,
                $value['nip'],
                $value['nama_lengkap'],
                $value['jenis_diklat'],
                $value['sub_jenis_diklat'],
                $value['rumpun_diklat'],
                $value['nama_diklat'],
                $value['tempat_diklat'],
                $value['penyelenggara_diklat'],
                $value['tgl_mulai'],
                $value['tgl_selesai'],
                $value['nomor_surat'],
                $value['tgl_surat'],
                $value['lama_pendidikan'],
                $value['tahun_angkatan'],
                $value['nomor_sttpp'],
                $value['tgl_sttpp'],
                $value['status'],
                $value['alasan'],
            );
            $i++;
        }

        return collect($laporan);
    }
}
