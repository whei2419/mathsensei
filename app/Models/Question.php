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

    protected $appends = ['level','step'];

    protected $hidden = ['hints'];

    public function getStepAttribute()
    {
        return $this->hints;
    }


    public function getLevelAttribute()
    {
        return $this->level()->first(); 
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function hints()
    {
        return $this->hasMany(Hint::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

}
