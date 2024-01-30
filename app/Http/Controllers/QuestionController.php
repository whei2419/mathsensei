<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function get(Request $request)
    {
        $questionsWithHints = Question::with('hints')->get();

        // Transform the data
        $transformedData = $questionsWithHints->map(function ($question) {
            return [
                'id' => $question->id,
                'name' => $question->name, // Assuming name is an attribute of the Question model
                'level' => [
                    'id' => $question->level->id,
                    'name' => $question->level->name,
                ],
                'solutions' => $question->hints->groupBy('solution_number')->map(function ($hints) {
                    return $hints->map(function ($hint) {
                        return [
                            'id' => $hint->id, 
                            'left' => $hint->left, 
                            'right' => $hint->right, 
                        ];
                    });
                }),
            ];
        });

        return response()->json(['questions'=>$transformedData], 200);

    }
}
