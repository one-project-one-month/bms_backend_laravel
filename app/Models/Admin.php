<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use  HasFactory, HasApiTokens, Notifiable;

    // protected $table = 'admins';
    protected $guarded = [];

    protected $fillable = [
        'name',
        'email',
        'managerId',
        'password',
        'adminCode',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

}
