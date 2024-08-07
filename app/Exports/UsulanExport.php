<?php

namespace App\Exports;

use App\Models\LaporanModel;
use App\Models\UsulanModel;
use Maatwebsite\Excel\Sheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsulanExport implements FromCollection
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

        $qry_status = DB::raw("CASE WHEN usulan.status = '0' THEN 'Ditolak' WHEN usulan.status = '1' THEN 'Disetujui' ELSE 'Ditinjau' END AS status");
        $qry_nip = DB::raw("CONCAT('\'', pegawai.nip) AS nip");

        $usulanQuery = UsulanModel::join('pengiriman', 'usulan.nip', '=', 'pengiriman.nip', 'left outer')->join('pegawai', 'usulan.nip', '=', 'pegawai.nip')
            ->select($qry_nip, 'pegawai.nama_lengkap', 'usulan.jenis_diklat', 'usulan.sub_jenis_diklat', 'usulan.rumpun_diklat', 'usulan.nama_diklat', 'usulan.dasar_usulan', $qry_status, 'usulan.alasan', 'pengiriman.id_usul')
            ->whereRaw($where)
            ->get()->toArray();

        // Excel Header
        $usulan = array();
        $usulan[] = array('#', 'NIP', 'Nama Lengkap', 'Jenis Diklat', 'Sub Jenis Diklat', 'Rumpun Diklat', 'Nama Diklat', 'Dasar Usulan', 'Status', 'Alasan');

        // Excel Body
        $i = 1;
        foreach ($usulanQuery as $key => $value) {

            if ($value['id_usul'] != null) {
                $value['status'] = 'Dikirim';
            }

            $usulan[] = array(
                $i,
                $value['nip'],
                $value['nama_lengkap'],
                $value['jenis_diklat'],
                $value['sub_jenis_diklat'],
                $value['rumpun_diklat'],
                $value['nama_diklat'],
                $value['dasar_usulan'],
                $value['status'],
                $value['alasan'],
            );
            $i++;
        }

        return collect($usulan);
    }
}
