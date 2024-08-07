<?php

namespace App\Models;

use App\Models\LMS\Course;
use App\Models\LMS\Enrollment;
use App\Models\LMS\RequestAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Blameable;

class PegawaiModel extends Model
{
    use HasFactory, Blameable;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    const CREATED_AT = 'entry_time';
    const UPDATED_AT = 'edit_time';
    protected $fillable = [
        'nip',
        'nama_lengkap',
        'kode_instansi',
        'entry_time',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id', 'nip');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'nip', 'user_id');
    }

    public function request_access()
    {
        return $this->hasOne(RequestAccess::class, 'nip', 'user_id');
    }
}
