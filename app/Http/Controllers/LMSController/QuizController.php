<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Answer;
use App\Models\LMS\Course;
use App\Models\LMS\Module;
use App\Models\LMS\Question;
use App\Models\LMS\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function show($quiz_id, $question_index = 0)
    {

        // Session::forget('quiz_answers');
        // Session::forget('quiz_questions');
        // Session::forget('quiz_start_time');
        // Session::forget('last_visited_lesson');

        // Load the quiz along with its questions
        $quiz = Quiz::with('questions.options', 'results')->findOrFail($quiz_id);

        $maxAttempts = 3;
        $shortWaitTime = 5 * 60; // 5 minutes in seconds
        $longWaitTime = 2 * 60 * 60; // 2 hours in seconds

        // Check if the user has passed the quiz
        $showStartQuizButton = !$quiz->results->contains('passed', true);
        $attemptCount = $quiz->results->count();
        $waitTime = $attemptCount < $maxAttempts ? $shortWaitTime : $longWaitTime;

        $countDownTime = 0;

        if ($attemptCount > 0) {
            $lastQuizResult = $quiz->results->last();
            $nextAttemptTime = strtotime($lastQuizResult->completed_at . " +{$waitTime} seconds");
            $now = strtotime(date('Y-m-d H:i:s'));
            $countDownTime = $nextAttemptTime - $now;
        }

        if ($countDownTime > 0) {
            $dataClass = Session::get('last_visited_lesson');
            return redirect()->route('lms.class.index', [$dataClass[0], $dataClass[1], $dataClass[2]])->with('error', 'Silahkan tunggu ' . gmdate("H:i:s", $countDownTime) . ' sebelum mencoba kuis lagi.');
        }

        // Shuffle questions if not already shuffled
        if (!Session::has('quiz_questions')) {
            $questions = $quiz->questions->shuffle()->values()->take($quiz->question_row);

            Session::put('quiz_questions', $questions);

            // Save the start time
            Session::put('quiz_start_time', now());
        } else {
            $questions = Session::get('quiz_questions');
        }

        // Check if question_index is within range
        $totalQuestions = $questions->count();
        if ($question_index < 0 || $question_index >= $totalQuestions) {
            abort(404);
        }

        $currentQuestion = $questions->get($question_index);

        // Calculate remaining time
        $startTime = Session::get('quiz_start_time');
        $elapsedTime = now()->diffInSeconds($startTime);
        $remainingTime = max(0, ($quiz->duration * 60) - $elapsedTime);

        return view('lms.class.quiz', [
            'quiz' => $quiz,
            'currentQuestion' => $currentQuestion,
            'question_index' => $question_index,
            'totalQuestions' => $totalQuestions,
            'questions' => $questions, // Ensure this is passed to the view
            'remainingTime' => $remainingTime,
            'showStartQuizButton' => $showStartQuizButton,
            'countDownTime' => $countDownTime,
        ]);
    }

    public function storeAnswer(Request $request, $quiz_id, $question_index)
    {
        // Validasi request
        $request->validate([
            'option_id' => 'required|integer|exists:lms_answer_options,option_id',
            'question_id' => 'required|integer|exists:lms_questions,question_id',
        ]);

        // Hitung waktu yang tersisa
        $startTime = Session::get('quiz_start_time');
        $quiz = Quiz::findOrFail($quiz_id);
        $elapsedTime = now()->diffInSeconds($startTime);
        $remainingTime = max(0, ($quiz->duration * 60) - $elapsedTime);

        if ($remainingTime <= 0) {
            // Waktu habis, otomatis akhiri kuis
            return $this->finish($request, $quiz_id);
        }

        // Ambil jawaban saat ini dari session
        $quizAnswers = Session::get('quiz_answers', []);

        // Simpan jawaban pengguna ke session
        $quizAnswers[$request->question_id] = $request->option_id;
        Session::put('quiz_answers', $quizAnswers);

        // Redirect ke pertanyaan berikutnya atau sebelumnya
        return redirect()->route('lms.class.quiz.show', [
            'quiz_id' => $quiz_id,
            'question_index' => $question_index + 1
        ]);
    }

    public function finish(Request $request, $quiz_id)
    {
        // Ambil jawaban saat ini dari session
        $quizAnswers = Session::get('quiz_answers', []);
        $dataClass = Session::get('last_visited_lesson');

        // Validasi request jika ada jawaban yang dikirim
        if ($request->has('option_id') && $request->has('question_id')) {
            $request->validate([
                'option_id' => 'required|integer|exists:lms_answer_options,option_id',
                'question_id' => 'required|integer|exists:lms_questions,question_id',
            ]);

            // Simpan jawaban pengguna ke session
            $quizAnswers[$request->question_id] = $request->option_id;
            Session::put('quiz_answers', $quizAnswers);
        }

        // Simpan semua jawaban ke database
        foreach ($quizAnswers as $question_id => $option_id) {
            DB::table('lms_answers')->insert([
                'question_id' => $question_id,
                'user_id' => Auth::user()->user_id,
                'option_id' => $option_id,
                'session_token' => $dataClass[3],
                'answered_at' => now()
            ]);
        }

        // Hitung skor
        $score = 0;
        $totalQuestions = count($quizAnswers);

        foreach ($quizAnswers as $question_id => $option_id) {
            $isCorrect = DB::table('lms_answer_options')
                ->where('option_id', $option_id)
                ->value('is_correct');

            if ($isCorrect) {
                $score++;
            }
        }

        // Skala skor menjadi dari 100
        if ($totalQuestions > 0) {
            $score = ($score / $totalQuestions) * 100;
        } else {
            $score = 0;
        }

        // Hitung status kelulusan
        $quiz = Quiz::findOrFail($quiz_id);
        $passingScore = $quiz->passing_score;
        $passed = $score >= $passingScore;

        // Simpan hasil
        DB::table('lms_quiz_results')->insert([
            'quiz_id' => $quiz_id,
            'user_id' => Auth::user()->user_id,
            'score' => $score,
            'passed' => $passed,
            'session_token' => $dataClass[3],
            'completed_at' => now()
        ]);

        // Hapus session
        Session::forget('quiz_answers');
        Session::forget('quiz_questions');
        Session::forget('quiz_start_time');
        Session::forget('last_visited_lesson');

        return redirect()->route('lms.class.index', [$dataClass[0], $dataClass[1], $dataClass[2]])->with('success', 'Kuis berhasil diselesaikan!');
    }
}
