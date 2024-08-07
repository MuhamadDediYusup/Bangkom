<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Category;
use App\Models\LMS\Course;
use App\Models\LMS\Enrollment;
use App\Models\LMS\Lesson;
use App\Models\LMS\LessonStatus;
use App\Models\LMS\Module;
use App\Models\LMS\RequestAccess;
use App\Models\LMS\Token;
use App\Repositories\CourseRepository;
use App\Repositories\Interfaces\CoursesInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{

    private $coursesInterface;

    public function __construct(CoursesInterface $coursesInterface)
    {
        $this->coursesInterface = $coursesInterface;
    }

    public function allCourse(Request $request)
    {
        $data = [
            'title' => 'Daftar Kursus',
            'courses' => Course::with('category', 'instructor')
                ->orderBy('entry_time', 'DESC')
                ->where('is_active', '1')
                ->when($request->search_course, function ($query) use ($request) {
                    $query->where('course_name', 'like', '%' . $request->search_course . '%');
                })
                ->get(),
            'categories' => Category::all(),
            'colors' => ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark'],
        ];

        return view('lms.courses.index', $data);
    }

    public function myCourse()
    {
        $data = [
            'title' => 'Kursus di Ikuti',
            'courses' => $this->coursesInterface->getMyCourse(auth()->user()->user_id)->get(),
            'categories' => Category::all(),
            'colors' => ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark'],
        ];

        return view('lms.courses.index', $data);
    }

    public function show($slug)
    {
        // Ambil data course dengan relasi yang diperlukan
        $course = Course::where('slug', $slug)
            ->with(['category', 'instructor', 'detail_course', 'modules.lessons', 'enrollments', 'request_access'])
            ->firstOrFail();

        $userId = auth()->user()->user_id;

        // Ambil status pelajaran terakhir yang selesai untuk setiap pelajaran dalam kursus
        $lessonsStatus = LessonStatus::where('user_id', $userId)
            ->whereIn('lesson_id', $course->modules->pluck('lessons.*.id')->flatten()->toArray())
            ->where('is_completed', 1)
            ->orderBy('completed_at', 'desc')
            ->get();

        // Iterasi melalui setiap modul dan pelajaran dalam kursus untuk memeriksa status pelajaran yang telah selesai
        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                $lessonStatus = $lessonsStatus->firstWhere('lesson_id', $lesson->id);
                if ($lessonStatus) {
                    $lesson->is_completed = true;
                    $lesson->completed_at = $lessonStatus->completed_at;
                } else {
                    $lesson->is_completed = false;
                    $lesson->completed_at = null;
                }
            }
        }

        // Ambil status pelajaran terakhir yang selesai secara umum
        $lastCompletedLessonStatus = $lessonsStatus->first();

        $data = [
            'title' => $course->course_name,
            'course' => $course,
            'lastCompletedLessonStatus' => $lastCompletedLessonStatus
        ];

        return view('lms.courses.show', $data);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if ($category) {
            $data = [
                'title' => 'Daftar Kursus',
                'courses' => Course::with('category', 'instructor')->where('category_id', $category->category_id)->get(),
                'categories' => Category::all(),
                'category_id' => $category->category_id,
                'colors' => ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark'],
            ];
        } else {
            $data = [
                'title' => 'Daftar Kursus',
                'courses' => Course::with('category', 'instructor')->get(),
                'categories' => Category::all(),
                'colors' => ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark'],
            ];
        }

        return view('lms.courses.index', $data);
    }

    // enroll course by token
    public function enrollToken(Request $request)
    {
        $token = Token::where('token', $request->token)
            ->where('course_id', $request->course_id)
            ->where('is_active', '1')
            ->first();

        if ($token) {
            $enrollment = new Enrollment();
            $enrollment->user_id = auth()->user()->user_id;
            $enrollment->course_id = $request->course_id;
            $enrollment->save();

            return redirect()->route('lms.course.mycourse')
                ->with('success', 'Kursus berhasil diikuti');
        } else {
            return redirect()->back()
                ->with('error', 'Token tidak valid');
        }
    }

    public function enrollRequest(Request $request)
    {
        $accessRequest = new RequestAccess();
        $accessRequest->user_id = auth()->user()->user_id;
        $accessRequest->course_id = $request->course_id;
        $accessRequest->requested_at = now();
        $accessRequest->status = '0';
        $accessRequest->save();

        return redirect()->back()
            ->with('success', 'Permintaan akses berhasil dikirim');
    }
}
