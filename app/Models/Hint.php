<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hint extends Model
{
    use HasFactory;

    public $table = 'hints';

    protected $fillable = [
        'question_id',
        'left',
        'right',
        'solution_number',
        'text'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
