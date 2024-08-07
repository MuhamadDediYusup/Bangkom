<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Blameable;

class Pengiriman extends Model
{
    use HasFactory;
    use Blameable;
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    const CREATED_AT = 'entry_time';
    const UPDATED_AT = 'edit_time';
    protected $fillable = [
        'id_usul',
        'nama',
        'nip',
        'nomor_surat',
        'tgl_surat',
        'status',
        'file_spt',
        'tempat_diklat',
        'penyelenggara_diklat',
        'tgl_mulai',
        'tgl_selesai',
        'entry_user',
        'entry_time',
        'edit_user',
        'edit_time',
    ];
}
