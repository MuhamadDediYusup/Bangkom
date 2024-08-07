<?php

namespace App\Exports;

use App\Models\LaporanModel;
use Maatwebsite\Excel\Sheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class JPBangkomExport implements FromCollection
{

    public $pd = null;
    public $jam = null;

    public function __construct($pd, $jam)
    {
        $this->pd = $pd;
        $this->jam = $jam;
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

        $pd = $this->pd == '00' ? null : $this->pd;
        try {
            $data = putData('20JP', $pd . '+' . $this->jam)->Data;
        } catch (\Throwable $th) {
            $data = [];
        }

        // dd($data);

        // Excel Header
        $laporan = array();
        $laporan[] = array('#', 'NIP', 'Nama Lengkap', 'Jabatan', 'Sub Satuan Organisasi', 'Satuan Organisasi', 'Perangkat Daerah', 'JP Manajerial', 'JP Teknis', 'JP Fungsional', 'JP Total');

        // Excel Body
        $i = 1;
        foreach ($data as $key => $value) {
            $laporan[] = array(
                $i,
                "`" . $value->nip,
                $value->nama_lengkap,
                $value->jabatan,
                $value->sub_satuan_organisasi,
                $value->satuan_organisasi,
                $value->perangkat_daerah,
                $value->jp_manajerial,
                $value->jp_teknis,
                $value->jp_fungsional,
                $value->jp_total,
            );
            $i++;
        }

        return collect($laporan);
    }
}
