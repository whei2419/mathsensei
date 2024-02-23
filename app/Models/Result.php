<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'random_tap',
        'hint_used',
        'wrong_try',
        'correct_try',
        'skip',
        'time_spent',
        'game_id',
        'question_id',
        'next',
        'extra_time',
        'sequence',


    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    
}
