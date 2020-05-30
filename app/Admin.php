<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden =  [
        'password', 'remember_token'
    ];

    public function articles()
    {
        return $this->hasMany('App\Article');
    }
}
