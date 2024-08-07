<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lms_lessons';
    protected $primaryKey = 'lesson_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'module_id',
        'lesson_name',
        'content',
        'content_type',
        'content_url',
        'estimated_time',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'entry_time',
        'edit_time'
    ];

    // Relationships
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function lessonStatuses()
    {
        return $this->hasOne(LessonStatus::class, 'lesson_id', 'lesson_id');
    }

    public function lessonQuiz()
    {
        return $this->hasOne(Quiz::class, 'lesson_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'lesson_id');
    }

    public function isLast()
    {
        return $this->module->lessons->last()->lesson_id == $this->lesson_id;
    }
}
