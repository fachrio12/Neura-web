<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'question_text',
    ];

    public $timestamps = false;


    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }


    public function options()
    {
        return $this->hasMany(Option::class);
    }


    public function answers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
