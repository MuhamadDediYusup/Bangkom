<?php

namespace App\Repositories\Interfaces;

interface CoursesInterface
{
    public function getAllCourses();
    public function getMyCourse($user_id);
    public function getProgressCourse($user_id, $course_id);
}
