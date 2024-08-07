<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'lms_announcements';
    protected $primaryKey = 'announcement_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'course_id',
        'announcement_title',
        'announcement_body',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'entry_time',
        'edit_time'
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
