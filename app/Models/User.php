<?php

namespace App\Models;

use App\Models\LMS\Answer;
use App\Models\LMS\Certificate;
use App\Models\LMS\Course;
use App\Models\LMS\Enrollment;
use App\Models\LMS\Grade;
use App\Models\LMS\LessonStatus;
use App\Models\LMS\Submission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = true;

    const CREATED_AT = 'entry_time';
    const UPDATED_AT = 'edit_time';
    protected $fillable = [
        'user_id',
        'user_name',
        'email',
        'password',
        'id_perangkat_daerah',
        'entry_user',
        'edit_user',
        'entry_time',
        'edit_time',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Set primary key yang bukan 'id'
    protected $primaryKey = 'id';

    // protected $keyType = 'bigint';
    public $incrementing = true;

    // Relasi dengan tabel lainnya
    // public function courses()
    // {
    //     return $this->hasMany(Course::class, 'instructor_id', 'user_id');
    // }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id', 'user_id');
    }

    public function lessonStatus()
    {
        return $this->hasMany(LessonStatus::class, 'user_id', 'user_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'user_id', 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'user_id', 'user_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'user_id', 'user_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id', 'user_id');
    }
}
