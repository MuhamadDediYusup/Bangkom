<?php

namespace App\Imports;

use App\Models\LMS\AnswerOption;
use App\Models\LMS\Question;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToCollection, WithHeadingRow
{
    protected $quiz_id;

    public function __construct($quiz_id)
    {
        $this->quiz_id = $quiz_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Check if question_text, quiz_id, and question_type are not empty
            if (!empty($row['question_text']) && !empty($this->quiz_id)) {
                // Insert question
                $question = Question::create([
                    'quiz_id' => $this->quiz_id,
                    'question_text' => $row['question_text'],
                    'question_type' => 'multiple_choice', // Default to 'multiple_choice'
                    'entry_user' => auth()->user()->id,  // Assuming you have authentication in place
                ]);

                // Insert options
                foreach (range(1, 4) as $index) {
                    if (!empty($row['option_text_' . $index])) {
                        AnswerOption::create([
                            'question_id' => $question->question_id,
                            'option_text' => $row['option_text_' . $index],
                            'is_correct' => isset($row['is_correct_' . $index]) && $row['is_correct_' . $index] == 1 ? 1 : 0,
                        ]);
                    }
                }
            }
        }
    }
}
