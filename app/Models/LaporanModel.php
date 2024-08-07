<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Blameable;

class LaporanModel extends Model
{
    use HasFactory, Blameable;

    public $timestamps = true;

    protected $table = 'laporan';
    protected $primaryKey = 'id_lapor';
    const CREATED_AT = 'entry_time';
    const UPDATED_AT = 'edit_time';
    protected $fillable = [
        'id_usul',
        'nip',
        'nama',
        'lama_pendidikan',
        'tahun_angkatan',
        'nomor_sttpp',
        'status',
        'alasan',
        'tgl_sttpp',
        'file_surat_laporan',
        'file_sttpp',
        'jenis_diklat',
        'sub_jenis_diklat',
        'penyelenggara_diklat',
        'id_diklat',
        'id_siasn',
        'sertifikat_siasn',
        'nomor_surat',
        'tgl_surat',
        'rumpun_diklat',
        'tgl_mulai',
        'tgl_selesai',
        'nama_diklat',
        'tempat_diklat',
        // 'entry_user',
        'entry_time',
        // 'edit_user',
        'edit_time',
    ];
}
