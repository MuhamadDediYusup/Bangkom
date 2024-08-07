<?php

namespace App\Models\LMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $table = 'lms_submissions';
    protected $primaryKey = 'submission_id';

    protected $fillable = [
        'assignment_id',
        'user_id',
        'submission_file',
        'submitted_at',
        'grade',
        'feedback',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'submitted_at'
    ];

    // Relationships
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
