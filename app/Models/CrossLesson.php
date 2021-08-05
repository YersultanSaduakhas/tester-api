<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrossLesson extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'lesson_id',
        'cross_lesson_id'
    ];
}
