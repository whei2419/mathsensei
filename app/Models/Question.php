<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public $table = 'questions';

    protected $fillable = [
        'name',
        'level_id',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

}
