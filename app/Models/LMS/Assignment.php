<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'lms_assignments';
    protected $primaryKey = 'assignment_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'course_id',
        'assignment_name',
        'description',
        'due_date',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'due_date',
        'entry_time',
        'edit_time'
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'assignment_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function getAssignment($lesson_id, $course_id)
    {
        return $this->where('lesson_id', $lesson_id)
            ->where('course_id', $course_id)
            ->first();
    }
}
