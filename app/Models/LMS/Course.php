<?php

namespace App\Models\LMS;

use App\Models\PegawaiModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'lms_courses';
    protected $primaryKey = 'course_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'course_name',
        'img_flyer',
        'slug',
        'description',
        'instructor_id',
        'instructor_name',
        'category_id',
        'entry_user',
        'edit_user',
        'is_active',
        'requires_token'
    ];

    public $timestamps = false;

    protected $dates = [
        'entry_time',
        'edit_time'
    ];

    // getMyCourse
    public static function getMyCourse($user_id)
    {
        return self::whereHas('enrollments', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->with('category', 'instructor', 'detail_course', 'modules.lessons');
    }

    // check enrolled course by user_id and course_id
    public static function checkEnrolledCourse($course_id, $user_id)
    {
        return self::where('course_id', $course_id)
            ->whereHas('enrollments', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->exists();
    }

    // Relationships
    public function detail_course()
    {
        return $this->belongsTo(CourseDetail::class, 'course_id', 'course_id');
    }

    public function instructor()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'course_id');
    }

    public function enrollments()
    {
        return $this->hasOne(Enrollment::class, 'course_id', 'course_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id', 'course_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'course_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'course_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'course_id');
    }

    public function tokens()
    {
        return $this->hasMany(Token::class, 'course_id');
    }

    public function request_access()
    {
        return $this->hasOne(RequestAccess::class, 'course_id');
    }
}
