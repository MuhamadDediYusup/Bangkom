<?php

namespace App\Exports;

use App\Models\LaporanModel;
use Maatwebsite\Excel\Sheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class KompetensiExport implements FromCollection
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

        // if ($this->id == 'all') {
        //     $where = 'pegawai.id_perangkat_daerah IS NOT NULL';
        // } else {
        //     $where = "pegawai.id_perangkat_daerah LIKE '" . $this->id . "%'";
        // }

        // $qry_status = DB::raw("CASE WHEN laporan.status = '0' THEN 'Ditolak' WHEN laporan.status = '1' THEN 'Disetujui' ELSE 'Ditinjau' END AS status");
        // $qry_nip = DB::raw("CONCAT('\'', pegawai.nip) AS nip");

        // $kompetensiQuery = LaporanModel::join('pegawai', 'laporan.nip', '=', 'pegawai.nip')
        //     ->select($qry_nip, 'pegawai.nama_lengkap', 'laporan.jenis_diklat', 'laporan.sub_jenis_diklat', 'laporan.rumpun_diklat', 'laporan.nama_diklat', 'laporan.tempat_diklat', 'laporan.penyelenggara_diklat', 'laporan.tgl_mulai', 'laporan.tgl_selesai', 'laporan.nomor_surat', 'laporan.tgl_surat', 'laporan.lama_pendidikan', 'laporan.tahun_angkatan', 'laporan.nomor_sttpp', 'laporan.tgl_sttpp', $qry_status, 'laporan.alasan')
        //     ->whereRaw($where)
        //     ->get()->toArray();

        $kompetensiQuery = putData('nominatif', $this->id)->Data;

        // dd($kompetensiQuery);

        // Excel Header
        $kompetensi = array();
        $kompetensi[] = array(
            '#', 'NIP', 'Nama Lengkap', 'Jabatan', 'Sub Satuan Organisasi', 'Satuan Organisasi', 'Perangkat Daerah', 'Jenis Diklat', 'Nama Diklat', 'Tempat Diklat',
            'Penyelenggara Diklat', 'Tanggal Mulai', 'Tanggal Selesai', 'Tanggal Surat', 'Lama Pendidikan',
            'Tahun Angkatan', 'Nomor STTPP', 'Tanggal STTPP'
        );

        // Excel Body
        $i = 1;
        foreach ($kompetensiQuery as $item) {
            $kompetensi[] = array(
                $i,
                `'` . $item->nip,
                $item->nama_lengkap,
                $item->jabatan,
                $item->sub_satuan_organisasi,
                $item->satuan_organisasi,
                $item->perangkat_daerah,
                $item->jenis_diklat,
                $item->nama_diklat,
                $item->tempat_diklat,
                $item->penyelenggara_diklat,
                $item->tahun_mulai,
                $item->tahun_selesai,
                $item->nomor_sttpp,
                $item->tgl_sttpp,
                $item->lama_pendidikan,
                $item->tahun_angkatan,
                $item->nomor_sttpp,
                $item->tgl_sttpp,
            );
            $i++;
        }

        return collect($kompetensi);
    }
}
