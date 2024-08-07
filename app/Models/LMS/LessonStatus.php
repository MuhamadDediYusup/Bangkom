<?php

namespace App\Models\LMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonStatus extends Model
{
    use HasFactory;

    protected $table = 'lms_lesson_status';
    protected $primaryKey = 'status_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'lesson_id',
        'user_id',
        'is_completed',
        'completed_at',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'completed_at'
    ];

    // Relationships
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function isCompleted($userId, $lessonId)
    {
        return self::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->exists();
    }
}
