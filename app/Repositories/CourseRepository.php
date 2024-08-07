<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Models\LMS\Course;
use App\Models\LMS\Lesson;
use App\Models\LMS\LessonStatus;
use App\Repositories\Interfaces\CoursesInterface;

class CourseRepository implements CoursesInterface
{
    /**
     * Get all courses
     */
    public function getAllCourses($returnType = 'object')
    {
        if ($returnType == 'json') {
            return Course::all()->toJson();
        }

        return Course::all();
    }

    /**
     * Get all courses with category and instructor module and lesson
     */
    public function getAllCoursesDetail()
    {
        return Course::with('category', 'instructor', 'detail_course', 'modules.lessons')->get();
    }

    /**
     * Get course by id
     */
    public function getCourseById($id)
    {
        return Course::find($id);
    }

    /**
     * Get all courses with category and instructor module and lesson
     */
    public function getDetailCourses($id)
    {
        return Course::with('category', 'instructor', 'detail_course', 'modules.lessons')->find($id);
    }

    /**
     * Get course with slug, module_id and lesson_id
     */
    public function getCourseWithSlug($slug)
    {
        return Course::where('slug', $slug)->with('category', 'instructor', 'detail_course', 'modules.lessons')->firstOrFail();
    }

    /**
     * Get my course
     */
    public function getMyCourse($user_id)
    {
        return Course::whereHas('enrollments', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->with('category', 'instructor', 'detail_course', 'modules.lessons');
    }

    /**
     * Get course with slug, module_id and lesson_id
     */
    public function getCourseWithSlugModuleLesson($slug, $module_id, $lesson_id)
    {
        return Course::where('slug', $slug)
            ->with(['category', 'instructor', 'detail_course', 'modules' => function ($query) use ($module_id) {
                $query->where('module_id', $module_id);
            }, 'modules.lessons' => function ($query) use ($lesson_id) {
                $query->where('lesson_id', $lesson_id);
            }])
            ->firstOrFail();
    }

    /**
     * Get progress course by user_id and module_id percentage of course completion and total course duration
     */
    public function getProgressCourse($course_id, $user_id)
    {
        $course = Course::with('modules.lessons')->find($course_id);

        $totalLessons = 0;
        foreach ($course->modules as $module) {
            $totalLessons += $module->lessons->count();
        }

        $completedLessons = LessonStatus::with('lesson')
            ->join('lms_lessons', 'lms_lessons.id', '=', 'lms_lesson_status.lesson_id')
            ->where('lms_lesson_status.user_id', $user_id)
            ->where('lms_lessons.course_id', $course_id)
            ->whereNotNull('lms_lesson_status.completed_at')
            ->get();
        dd($completedLessons);

        if ($totalLessons == 0) {
            return 0;
        }

        $progress = ($completedLessons->count() / $totalLessons) * 100;

        dd($progress);
        return $progress;
    }
}
