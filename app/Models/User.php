<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     *
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'birth_date',
        'gender',
        'role_id',
    ];

    /**
     * .
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     *
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];


    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = $value;
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function sessions()
    {
        return $this->hasMany(UserAssessmentSession::class);
    }


    public function isAdmin()
    {
        return $this->role_id === 1;
    }
}
