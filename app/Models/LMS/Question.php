<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'lms_questions';
    protected $primaryKey = 'question_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'question_id',
        'quiz_id',
        'question_text',
        'question_image',
        'question_type',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'entry_time',
        'edit_time'
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function options()
    {
        return $this->hasMany(AnswerOption::class, 'question_id');
    }
}
