<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function get(Request $request)
    {
        $question = Question::get();

        return response()->json(['questions'=>$question], 200);

    }
}
