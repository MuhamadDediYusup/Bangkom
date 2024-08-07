<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'lms_quizzes';
    protected $primaryKey = 'quiz_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'course_id',
        'lesson_id',
        'quiz_name',
        'description',
        'duration',
        'passing_score',
        'weight_percentage',
        'question_row',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'entry_time',
        'edit_time'
    ];

    // Relationships
    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }

    public function lessons()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }

    public function getQuiz($lesson_id, $course_id)
    {
        return $this->where('lesson_id', $lesson_id)
            ->where('course_id', $course_id)
            ->first();
    }
}
