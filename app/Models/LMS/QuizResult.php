<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pegawai;

class QuizResult extends Model
{
    use HasFactory;

    protected $table = 'lms_quiz_results';
    protected $primaryKey = 'result_id';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'passed',
        'session_token',
        'completed_at'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function user()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'user_id');
    }
}
