<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssessmentSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assessment_id',
    ];

    protected $casts = [
        'taken_at' => 'datetime',
    ];


    const CREATED_AT = 'taken_at';
    const UPDATED_AT = null;


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }


    public function answers()
    {
        return $this->hasMany(UserAnswer::class, 'session_id');
    }


    public function results()
    {
        return $this->hasMany(AssessmentResult::class, 'session_id');
    }


    public function motivationFactors()
    {
        return $this->hasMany(MotivationFactor::class, 'session_id');
    }


    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'session_id');
    }
}
