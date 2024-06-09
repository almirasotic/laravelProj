<?php

namespace App\Models;

use App\Models\Themes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'place_of_birth',
        'country',
        'birth_date',
        'personal_number',
        'phone_number',
        'picture'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function themes()
    {
        return $this->hasMany(Themes::class, 'user_id');
    }

    public function followedThemes()
    {
        return $this->belongsToMany(Themes::class, 'user_theme_follows', 'user_id', 'theme_id');
    }
}
