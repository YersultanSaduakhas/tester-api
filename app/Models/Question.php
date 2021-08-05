<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Option;

class Question extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'questions';
    protected $guarded = array();
    
    protected $fillable = [
        'lesson_id',
        'text',
        'answers',
        'reason',
        'is_5_optioned',
        'hint',
        'tmp', 
        'tmp_question_id'
    ];

    public function options() {
        return $this->hasMany('App\Models\Option');
    }
}
