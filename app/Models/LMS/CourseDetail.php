<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDetail extends Model
{
    use HasFactory;

    protected $table = 'lms_course_details';
    protected $primaryKey = 'course_detail_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'course_id',
        'detail_course',
        'target_participants',
        'objectives',
        'competence',
        'total_hours',
        'start_date',
        'end_date'
    ];

    protected $dates = [
        'entry_time',
        'edit_time'
    ];

    // Relationships
    public function courses()
    {
        return $this->hasMany(Course::class, 'course_id', 'course_id');
    }
}
