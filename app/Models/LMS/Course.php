<?php

namespace App\Models\LMS;

use App\Models\PegawaiModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory;

    protected $table = 'lms_courses';
    protected $primaryKey = 'course_id';

    const CREATED_AT = 'entry_time';
    const UPDATED_AT = 'edit_time';

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

    public $timestamps = true; // Enable timestamps

    protected $dates = [
        'entry_time',
        'edit_time'
    ];

    public static function getMyCourse($user_id)
    {
        // Using subquery to calculate progress for each course
        return self::select('lms_courses.*')
            ->whereHas('enrollments', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->with('category', 'instructor', 'detail_course', 'modules.lessons')
            ->leftJoin('lms_modules', 'lms_courses.course_id', '=', 'lms_modules.course_id')
            ->leftJoin('lms_lessons', 'lms_modules.module_id', '=', 'lms_lessons.module_id')
            ->leftJoin('lms_lesson_status', function ($join) use ($user_id) {
                $join->on('lms_lessons.lesson_id', '=', 'lms_lesson_status.lesson_id')
                    ->where('lms_lesson_status.user_id', '=', $user_id)
                    ->where('lms_lesson_status.is_completed', '=', 1);
            })
            ->groupBy('lms_courses.course_id')
            ->orderByRaw('COUNT(lms_lesson_status.status_id) DESC')
            ->get();
    }

    public static function checkEnrolledCourse($course_id, $user_id)
    {
        return self::where('course_id', $course_id)
            ->whereHas('enrollments', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->first();
    }

    public function getProgressAttribute()
    {
        $totalLessons = $this->modules()->withCount(['lessons as completed_lessons_count' => function ($query) {
            $query->whereHas('lessonStatus', function ($query) {
                $query->where('user_id', Auth::id())
                    ->where('is_completed', 1);
            });
        }])
            ->get()
            ->sum('lessons_count');

        $completedLessons = $this->modules()->withCount(['lessons as completed_lessons_count' => function ($query) {
            $query->whereHas('lessonStatus', function ($query) {
                $query->where('user_id', Auth::id())
                    ->where('is_completed', 1);
            });
        }])
            ->get()
            ->sum('completed_lessons_count');

        return $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
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
        return $this->hasMany(Enrollment::class, 'course_id', 'course_id');
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
