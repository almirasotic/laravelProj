<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Themes extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'user_id', 'image'];

    
    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%');
        }
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function comments()
    {
        return $this->hasMany(Comment::class, 'theme_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_theme_follows', 'theme_id', 'user_id');
    }

    public function polls()
    {
        return $this->hasMany(Poll::class);
    }

}
