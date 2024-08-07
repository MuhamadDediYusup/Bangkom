<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Imports\QuestionsImport;
use App\Models\LMS\AnswerOption;
use App\Models\LMS\Question;
use App\Models\LMS\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;


class AdminQuestionController extends Controller
{
    public function index($quiz_id)
    {

        $quiz = Quiz::findOrFail($quiz_id);

        $data = [
            'title' => 'Manajemen Pertanyaan',
            'quiz' => $quiz,
        ];

        return view('lms.admin.question.index', $data);
    }

    public function getAllQuestion($quiz_id)
    {
        $data = Question::with('options')
            ->where('quiz_id', $quiz_id)
            ->orderBy('entry_time', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->question_id . '" data-name="' . $row->question_text . '">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create($quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $question = Question::where('quiz_id', $quiz_id)->get();

        $data = [
            'title' => 'Tambah Pertanyaan',
            'quiz' => $quiz,
            'questions' => $question,
        ];

        return view('lms.admin.question.create', $data);
    }

    public function store(Request $request)
    {

        $data = $request->all();

        if (!isset($data['questions'])) {
            return redirect()->route('lms.admin.question.index', $data['quiz_id'])->with('error', 'Tidak ada Data Baru!');
        }

        // validation
        $request->validate([
            'quiz_id' => 'required|integer|exists:lms_quizzes,quiz_id',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|string|in:multiple_choice,true_false,short_answer',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.is_correct' => 'nullable|integer',
        ]);

        foreach ($data['questions'] as $questionData) {

            if (isset($questionData['image']) && $questionData['image'] instanceof \Illuminate\Http\UploadedFile) {
                // Define the path within the public directory
                $destinationPath = 'files/quiz/' . $data['quiz_id'];

                // Move the file to the specified path in the public directory
                $filename = $questionData['image']->getClientOriginalName();
                $questionData['image']->move(public_path($destinationPath), $filename);

                // Save the path in the database
                $questionData['question_image'] = $destinationPath . '/' . $filename;
            }

            // Insert question
            $question = Question::create([
                'quiz_id' => $data['quiz_id'],
                'question_text' => $questionData['question_text'],
                'question_image' => $questionData['question_image'] ?? null,
                'question_type' => $questionData['question_type'],
            ]);

            // Insert options
            foreach ($questionData['options'] as $index => $optionData) {
                AnswerOption::create([
                    'question_id' => $question->question_id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $index == $questionData['is_correct'] ? 1 : 0,
                ]);
            }
        }

        return redirect()->route('lms.admin.question.index', $data['quiz_id'])->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    // batch edit from quiz
    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        $question = Question::where('quiz_id', $id)->get();
        $data = [
            'title' => 'Edit Pertanyaan',
            'questions' => $question,
            'quiz' => $quiz,
        ];

        return view('lms.admin.question.edit', $data);
    }


    public function update(Request $request, $id)
    {

        $data = $request->all();

        if (!isset($data['questions'])) {
            return redirect()->route('lms.admin.question.index', $id)->with('error', 'Tidak ada Data Baru!');
        }

        // Validasi input jika diperlukan
        $request->validate([
            'quiz_id' => 'required|integer|exists:lms_quizzes,quiz_id',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|string|in:multiple_choice,true_false,short_answer',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.is_correct' => 'nullable|integer',
        ]);

        // Delete all existing questions and options for the quiz
        $existingQuestions = Question::where('quiz_id', $id)->get();
        foreach ($existingQuestions as $existingQuestion) {
            // Delete options
            foreach ($existingQuestion->options as $option) {
                $option->delete();
            }

            // Delete question image if it exists
            if ($existingQuestion->image) {
                $imagePath = public_path($existingQuestion->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            // Delete question
            $existingQuestion->delete();
        }

        // Insert new questions and options
        foreach ($data['questions'] as $questionData) {
            // Insert question
            $question = Question::create([
                'quiz_id' => $id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
            ]);

            // Handle question image upload if it exists
            if (isset($questionData['image']) && $questionData['image'] instanceof \Illuminate\Http\UploadedFile) {
                // Define the path within the public directory
                $destinationPath = 'files/quiz/' . $id;

                // Ensure the directory exists
                if (!file_exists(public_path($destinationPath))) {
                    mkdir(public_path($destinationPath), 0755, true);
                }

                // Move the file to the specified path in the public directory
                $filename = $questionData['image']->getClientOriginalName();
                $questionData['image']->move(public_path($destinationPath), $filename);

                // Save the path in the database
                $question->image = $destinationPath . '/' . $filename;
                $question->save();
            }

            // Insert options
            foreach ($questionData['options'] as $index => $optionData) {
                AnswerOption::create([
                    'question_id' => $question->question_id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $index == $questionData['is_correct'] ? 1 : 0,
                ]);
            }
        }


        return redirect()->route('lms.admin.question.index', $id)->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->options()->delete();
        $question->delete();

        return redirect()->back()->with('success', 'Pertanyaan Berhasil di Hapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'quiz_id' => 'required|integer'
        ]);

        $quiz_id = $request->input('quiz_id');

        Excel::import(new QuestionsImport($quiz_id), $request->file('file'));

        return back()->with('success', 'Pertanyaan berhasil diimpor.');
    }
}
