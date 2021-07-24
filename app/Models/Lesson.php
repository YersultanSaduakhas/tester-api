<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'question_count',
        'question_count_to_test',
        'language'
    ];
}
