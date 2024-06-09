<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollResponse extends Model
{
    use HasFactory;

    protected $fillable = ['poll_id', 'theme_id', 'user_id', 'response'];


    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    
    public function theme()
    {
        return $this->belongsTo(Themes::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
