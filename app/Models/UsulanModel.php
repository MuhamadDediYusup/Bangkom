<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class UsulanModel extends Model
{
    use HasFactory;
    use Blameable;
    protected $table = 'usulan';
    protected $primaryKey = 'id_usul';
    const CREATED_AT = 'entry_time';
    const UPDATED_AT = 'edit_time';
    protected $fillable = [
        'id_usul',
        'nip',
        'nama',
        'jenis_diklat',
        'sub_jenis_diklat',
        'rumpun_diklat',
        'nama_diklat',
        'status',
        'alasan',
        'entry_user',
        'entry_time',
        'edit_user',
        'edit_time',
    ];

    public function pegawai()
    {
        return $this->belongsTo(PegawaiModel::class, 'nip', 'nip');
    }
}
