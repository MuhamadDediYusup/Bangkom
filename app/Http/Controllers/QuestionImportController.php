<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;

class QuestionImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new QuestionsImport, $request->file('file'));

        return back()->with('success', 'Questions Imported Successfully.');
    }
}
