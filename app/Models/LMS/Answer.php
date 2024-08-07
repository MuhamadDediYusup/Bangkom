<?php

namespace App\Models\LMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pegawai;

class Answer extends Model
{
    use HasFactory;

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $table = 'lms_answers';
    protected $primaryKey = 'answer_id';

    protected $fillable = [
        'answer_id',
        'question_id',
        'user_id',
        'option_id',
        'answered_at',
        'session_token',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'answered_at'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'user_id');
    }

    public function answerOption()
    {
        return $this->belongsTo(AnswerOption::class, 'option_id');
    }
}
