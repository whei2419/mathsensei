<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Game;
use App\Models\Result;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function newGame(Request $request)
    {
        $validateUser = Validator::make($request->all(), 
        [
            'user_id' => 'required|exists:users,id'
        ]);

        $game = new Game();
        $game->user_id = $request->user_id;
        $game->save();

        return response()->json([
            'status' => true,
            'message' => 'Game Created Successfully',
            'game' => $game,
        ], 200);
    }

    public function results(Request $request)
    {
        $validateUser = Validator::make($request->all(), 
         [
            'question_id' => 'required|exists:questions,id',
            'game_id' => 'required|exists:games,id'
        ]);

        $result = new Result;
        $result->random_tap = $request->random_tap;
        $result->hint_used = $request->hint_used;
        $result->wrong_try = $request->wrong_try;
        $result->correct_try = $request->correct_try;
        $result->next = $request->next;
        $result->extra_time = $request->extra_time;
        $result->skip = $request->skip;
        $result->time_spent = $request->time_spent;
        $result->question_id = $request->question_id;
        $result->game_id = $request->game_id;
        $result->sequence = $request->sequence;
        $result->save();

        return response()->json([
            'status' => true,
            'result' => $result,
        ], 200);
    }

    public function score(Request $request)
    {
        $validateUser = Validator::make($request->all(), 
        [
           'game_id' => 'required|exists:games,id'
       ]);

       $result = Result::where('game_id',$request->game_id)->sum('correct_try');

       return response()->json([
        'status' => true,
        'score' => $result,
    ], 200);
    }
}
