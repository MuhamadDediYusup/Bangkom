<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Answer;
use App\Models\LMS\AnswerOption;
use App\Models\LMS\Category;
use App\Models\LMS\Course;
use App\Models\LMS\Enrollment;
use App\Models\LMS\Lesson;
use App\Models\LMS\LessonStatus;
use App\Models\LMS\Module;
use App\Models\LMS\Question;
use App\Models\LMS\Quiz;
use App\Models\LMS\QuizResult;
use App\Models\LMS\RequestAccess;
use App\Models\LMS\Token;
use App\Repositories\CourseRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lms-list', ['only' => ['index', 'getCourseData']]);
        $this->middleware('permission:lms-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:lms-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:lms-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Kursus',
        ];

        return view('lms.admin.courses.index', $data);
    }

    public function getCourseData(Request $request)
    {
        $data = Course::select(['course_id', 'img_flyer', 'course_name', 'slug', 'instructor_name', 'is_active'])->orderBy('entry_time', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (auth()->user()->hasPermissionTo('lms-list')) {
                    $btn .= '<a href="' . route('lms.admin.module.index', $row->slug) . '" class="btn btn-primary btn-sm">Modul</a>';
                }

                if (auth()->user()->hasPermissionTo('lms-edit')) {
                    $btn .= '<a href="' . route('lms.admin.course.edit', $row->course_id) . '" class="btn btn-success btn-sm my-md-1 mx-sm-1">Edit</a>';
                }

                if (auth()->user()->hasPermissionTo('lms-delete')) {
                    $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->course_id . '" data-name="' . $row->course_name . '">Delete</button>';
                }
                return $btn;
            })
            ->addColumn('photo', function ($row) {
                $url = asset('course/flyer/' . '/' . $row->img_flyer);
                return '<img src="' . $url . '" alt="Flyer" height="50"/>';
            })
            ->rawColumns(['action', 'photo'])
            ->make(true);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kursus',
            'categories' => Category::all(),
        ];

        return view('lms.admin.courses.create', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'course_name' => 'required',
            'img_flyer' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'requires_token' => 'required',
            'detail_course' => 'required',
            'target_participants' => 'required',
            'objectives' => 'required',
            'competence' => 'required',
            'total_hours' => 'required',
        ]);

        $image_name = null;
        if (isset($request->img_flyer) && $request->img_flyer != null) {
            $image = $request->img_flyer;
            $image_name = $request->slug . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/course/flyer/');
            $image->move($destinationPath, $image_name);
        }

        // Store the course and detailcourse
        $course = Course::create([
            'course_name' => $request->course_name,
            'img_flyer' => $image_name,
            'slug' => $request->slug,
            'description' => $request->description,
            'instructor_id' => $request->instructor_id,
            'instructor_name' => $request->instructor_name,
            'category_id' => $request->category_id,
            'requires_token' => $request->requires_token,
            'is_active' => $request->is_active,
        ]);

        $dates = explode(' - ', $request->date);
        $start_date = isset($dates[0]) ? $dates[0] : null;
        $end_date = isset($dates[1]) ? $dates[1] : null;

        $course->detail_course()->create([
            'course_id' => $course->course_id,
            'detail_course' => $request->detail_course,
            'target_participants' => $request->target_participants,
            'objectives' => $request->objectives,
            'competence' => $request->competence,
            'total_hours' => $request->total_hours,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        return redirect()->route('lms.admin.course.index')->with('success', 'Kursus berhasil dibuat.');
    }

    public function edit($id)
    {
        $course = Course::with('detail_course')->where('course_id', $id)->first();
        $data = [
            'title' => 'Edit Kursus',
            'course' => $course,
            'categories' => Category::all(),
        ];

        return view('lms.admin.courses.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'requires_token' => 'required',
            'detail_course' => 'required',
            'target_participants' => 'required',
            'objectives' => 'required',
            'competence' => 'required',
            'total_hours' => 'required',
        ]);

        $request['course_id'] = $id;

        $course = Course::with('detail_course')->where('course_id', $request->course_id)->firstOrFail();

        $image_name = null;
        if (isset($request->img_flyer) && $request->img_flyer != null) {
            $image = $request->img_flyer;
            $image_name = $request->slug . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/course/flyer/');
            $image->move($destinationPath, $image_name);

            $course->update([
                'img_flyer' => $image_name,
            ]);
        }

        $course->update([
            'course_name' => $request->course_name,
            'slug' => $request->slug,
            'description' => $request->description,
            'instructor_id' => $request->instructor_id,
            'instructor_name' => $request->instructor_name,
            'category_id' => $request->category_id,
            'requires_token' => $request->requires_token,
            'is_active' => $request->is_active,
        ]);

        $dates = explode(' - ', $request->date);
        $start_date = isset($dates[0]) ? $dates[0] : null;
        $end_date = isset($dates[1]) ? $dates[1] : null;

        $course->detail_course()->update([
            'course_id' => $course->course_id,
            'detail_course' => $request->detail_course,
            'target_participants' => $request->target_participants,
            'objectives' => $request->objectives,
            'competence' => $request->competence,
            'total_hours' => $request->total_hours,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        return redirect()->route('lms.admin.course.index')->with('success', 'Kursus berhasil diupdate.');
    }

    public function destroy($id)
    {

        // try {
        // delete request access
        $requestAccrss = RequestAccess::where('course_id', $id)->get();
        foreach ($requestAccrss as $request) {
            $request->delete();
        }

        // delete enrollments
        $enrollments = Enrollment::where('course_id', $id)->get();
        foreach ($enrollments as $enrollment) {
            $enrollment->delete();
        }

        // delete token
        $tokens = Token::where('course_id', $id)->get();
        foreach ($tokens as $token) {
            $token->delete();
        }

        // delete module
        $modules = Module::where('course_id', $id)->get();
        foreach ($modules as $module) {
            // delete lesson
            $lessons = Lesson::where('module_id', $module->module_id)->get();
            foreach ($lessons as $lesson) {
                // delete quiz and quiz question if exist
                $quiz = Quiz::where('lesson_id', $lesson->lesson_id)->get();
                foreach ($quiz as $q) {
                    $questions = Question::where('quiz_id', $q->quiz_id)->get();
                    foreach ($questions as $question) {

                        // delete answers option
                        $answersOption = AnswerOption::where('question_id', $question->question_id)->get();
                        foreach ($answersOption as $option) {
                            $option->delete();
                        }

                        // delete answers
                        $answers = Answer::where('question_id', $question->question_id)->get();
                        foreach ($answers as $answer) {
                            $answer->delete();
                        }

                        $question->delete();
                    }

                    $quizResults = QuizResult::where('quiz_id', $q->quiz_id)->get();
                    foreach ($quizResults as $result) {
                        $result->delete();
                    }

                    $q->delete();
                }

                // delete lesson status
                $lessonStatus = LessonStatus::where('lesson_id', $lesson->lesson_id)->get();
                foreach ($lessonStatus as $status) {
                    $status->delete();
                }

                $lesson->delete();
            }

            $module->delete();
        }


        $course = Course::with('detail_course')->where('course_id', $id)->firstOrFail();

        if ($course->img_flyer != null) {
            $image_path = public_path('/course/flyer/' . $course->img_flyer);
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $course->detail_course()->delete();
        $course->delete();

        return redirect()->route('lms.admin.course.index')->with('success', 'Kursus berhasil dihapus.');
        // } catch (\Throwable $th) {
        //     return redirect()->route('lms.admin.course.index')->with('error', 'Kursus gagal dihapus, pastikan tidak ada data terkait kursus ini.');
        // }
    }
}
