<?php

namespace App\Models\LMS;

use App\Models\PegawaiModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'lms_enrollments';
    protected $primaryKey = 'enrollment_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'user_id',
        'course_id',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'enrolled_at'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function employee()
    {
        return $this->belongsTo(PegawaiModel::class, 'user_id', 'nip');
    }
}
