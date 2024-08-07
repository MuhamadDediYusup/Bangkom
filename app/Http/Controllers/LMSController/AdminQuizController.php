<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Answer;
use App\Models\LMS\AnswerOption;
use App\Models\LMS\Course;
use App\Models\LMS\Lesson;
use App\Models\LMS\Module;
use App\Models\LMS\Question;
use App\Models\LMS\Quiz;
use App\Models\LMS\QuizResult;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminQuizController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Manajemen Kuis',
            'courses' => Course::whereHas('modules.lessons', function ($query) {
                $query->where('content_type', 'quiz');
            })->get(),
        ];

        return view('lms.admin.quiz.index', $data);
    }

    public function getQuizData($slug = null)
    {

        // jika slug null maka cek apakah session course_slug ada atau tidak jika ada maka ambil dari session course_slug
        if ($slug) {
            session(['course_slug' => $slug]);
        } else {
            // Jika slug tidak ada di request, ambil dari session jika ada
            if (is_null($slug) && session()->has('course_slug')) {
                $slug = session('course_slug');
            }
        }

        if ($slug) {
            $course_id = Course::where('slug', $slug)->first()->course_id;
            $data = Quiz::with('questions', 'lessons', 'courses')
                ->whereHas('lessons', function ($query) use ($course_id) {
                    $query->where('course_id', $course_id);
                })
                ->orderBy('entry_time', 'desc');
        } else {
            $data = Quiz::with('questions', 'lessons', 'courses')
                ->orderBy('entry_time', 'desc');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $btn .= '<a href="' . route('lms.admin.question.index', $row->quiz_id) . '" class="btn btn-primary btn-sm my-md-1 mx-sm-1">Pertanyaan</a>';
                $btn .= '<a href="' . route('lms.admin.quiz.edit', $row->quiz_id) . '" class="btn btn-success btn-sm my-md-1 mx-sm-1">Edit</a>';
                $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->quiz_id . '" data-name="' . $row->quiz_title . '">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create($slug = null)
    {
        if ($slug) {
            // Ambil kursus berdasarkan slug
            $course = Course::where('slug', $slug)->first();

            if (!$course) {
                return redirect()->back()->with('error', 'Kursus tidak ditemukan!');
            }

            // Ambil ID modul yang terkait dengan kursus
            $moduleIds = Module::where('course_id', $course->course_id)->pluck('module_id');

            // Ambil pelajaran berdasarkan ID modul yang belum memiliki quiz
            $lessons = Lesson::whereIn('module_id', $moduleIds)
                ->where('content_type', 'quiz')
                ->whereDoesntHave('lessonQuiz')
                ->get();

            $data = [
                'title' => 'Tambah Kuis',
                'course' => $course,
                'lesson' => $lessons,
            ];

            return view('lms.admin.quiz.create', $data);
        } else {
            return redirect()->back()->with('error', 'Silahkan pilih kursus terlebih dahulu!');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'lesson_id' => 'required',
            'quiz_name' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'passing_score' => 'required',
            'weight_percentage' => 'required',
            'question_row' => 'required',
        ]);

        $quiz = new Quiz();
        $quiz->course_id = $request->course_id;
        $quiz->lesson_id = $request->lesson_id;
        $quiz->quiz_name = $request->quiz_name;
        $quiz->description = $request->description;
        $quiz->duration = $request->duration;
        $quiz->passing_score = $request->passing_score;
        $quiz->weight_percentage = $request->weight_percentage;
        $quiz->question_row = $request->question_row;
        $quiz->entry_time = now();
        $quiz->save();

        return redirect()->route('lms.admin.quiz.index')->with('success', 'Kuis berhasil ditambahkan!');
    }

    public function edit($quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $course = Course::where('course_id', $quiz->course_id)->first();

        $data = [
            'title' => 'Edit Kuis',
            'quiz' => $quiz,
            'lesson' => Lesson::where('content_type', 'quiz')->get(),
            'course' => $course,
        ];

        return view('lms.admin.quiz.edit', $data);
    }

    public function update(Request $request, $quiz_id)
    {
        $request->validate([
            'course_id' => 'required',
            'lesson_id' => 'required',
            'quiz_name' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'passing_score' => 'required',
            'weight_percentage' => 'required',
            'question_row' => 'required',
        ]);

        $quiz = Quiz::findOrFail($quiz_id);
        $quiz->course_id = $request->course_id;
        $quiz->lesson_id = $request->lesson_id;
        $quiz->quiz_name = $request->quiz_name;
        $quiz->description = $request->description;
        $quiz->duration = $request->duration;
        $quiz->passing_score = $request->passing_score;
        $quiz->weight_percentage = $request->weight_percentage;
        $quiz->question_row = $request->question_row;
        $quiz->save();

        return redirect()->route('lms.admin.quiz.index')->with('success', 'Kuis berhasil diubah!');
    }

    public function destroy($quiz_id)
    {

        // delete semua data yang berhubungan dengan quiz mulai dari question, answer, answer option, quiz result, dan quiz
        $questions = Question::where('quiz_id', $quiz_id)->get();
        foreach ($questions as $question) {
            $answers = Answer::where('question_id', $question->question_id)->get();
            foreach ($answers as $answer) {
                $answerOptions = AnswerOption::where('option_id', $answer->option_id)->get();
                foreach ($answerOptions as $answerOption) {
                    $answerOption->delete();
                }
                $answer->delete();
            }
            $question->delete();
        }

        $quizResults = QuizResult::where('quiz_id', $quiz_id)->get();
        foreach ($quizResults as $quizResult) {
            $quizResult->delete();
        }

        $quiz = Quiz::findOrFail($quiz_id);
        $quiz->delete();

        return redirect()->route('lms.admin.quiz.index')->with('success', 'Data Kuis, Pertanyaan, Jawaban, dan Hasil Kuis berhasil dihapus!');
    }
}
