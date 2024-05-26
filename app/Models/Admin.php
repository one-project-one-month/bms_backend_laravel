<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use  HasFactory, HasApiTokens, Notifiable;

    // protected $table = 'admins';
    protected $guarded = [];

    protected $fillable = [
        'email',
        'userName',
        'DOB',
        'townshipCode',
        'role',
        'password',
        'adminCode',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}
