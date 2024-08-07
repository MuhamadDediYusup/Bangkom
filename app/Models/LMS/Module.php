<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $table = 'lms_modules';
    protected $primaryKey = 'module_id';

    protected $fillable = [
        'course_id',
        'module_name',
        'description',
        'entry_user',
        'edit_user'
    ];

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

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

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'module_id', 'module_id');
    }

    public function isLast()
    {
        return $this->course->modules->last()->module_id == $this->module_id;
    }
}
