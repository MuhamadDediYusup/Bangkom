<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerOption extends Model
{
    use HasFactory;

    protected $table = 'lms_answer_options';
    protected $primaryKey = 'option_id';
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
