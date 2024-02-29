<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function get(Request $request)
    {
        $query = Question::query();

        switch (auth()->user()->type) {
            case 1:
                $query->orderByRaw('level_id ASC');
                break;
            case 2:
                $query->orderByRaw('level_id DESC');
                break;
            case 3:
                $query->inRandomOrder();
                break;
            default:
        }
        
        
        
        $questions = $query->get();
        
        $questionsWithHints = Question::with('hints')->get();
        // return $questions;

        $transformedData = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question_text' => $question->name, // Adjusted from 'name' to 'question_text'
                'level' => [
                    'id' => $question->level->id,
                    'name' => $question->level->name,
                    'color' => $question->level->color,

                ],
                'solutions' => $question->hints->groupBy('solution_number')->map(function ($hints) {
                    return $hints->map(function ($hint) {
                        return [
                            'id' => $hint->id, 
                            'text' => $hint->text, 
                            'left' => $hint->left, 
                            'right' => $hint->right, 
                        ];
                    });
                }),
            ];
        });
        
        return response()->json(['questions' => $transformedData], 200);
        

    }
}
