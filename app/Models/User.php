<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Socialite\Facades\Socialite;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'google_id', 'avatar', 'role', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}