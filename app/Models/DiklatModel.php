<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Blameable;

class DiklatModel extends Model
{
    use HasFactory, Blameable;

    protected $table = 'diklat';
    protected $primaryKey = 'id';
    const CREATED_AT = 'entry_time';
    protected $fillable = ['jenis_diklat', 'sub_jenis_diklat', 'rumpun_diklat', 'id_diklat', 'id_siasn', 'sertifikat_siasn', 'entry_time'];
}
