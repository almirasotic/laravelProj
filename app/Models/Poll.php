<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme_id',
        'question',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    
    public function theme()
    {
        return $this->belongsTo(Themes::class, 'theme_id');
    }

}
