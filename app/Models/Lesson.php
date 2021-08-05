<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'l_type',
        'name',
        'question_count',
        'question_count_to_test',
        'language'
    ];

    public function cross_lessons()
    {
    return $this->belongsToMany(Lesson::class, 'cross_lessons', 'lesson_id', 'cross_lesson_id');
    }

    // Same table, self referencing, but change the key order
    public function theFriends()
    {
    return $this->belongsToMany(Lesson::class, 'cross_lessons', 'cross_lesson_id', 'lesson_id');
    }
}
