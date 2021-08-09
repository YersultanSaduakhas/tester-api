<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->integer('lesson_id');
            $table->string('text');
            $table->string('answers');
            $table->string('reason');
            $table->boolean('is_5_optioned');
            $table->integer('right_answer_count');
            $table->string('hint');
            $table->boolean('tmp');
            $table->string('tmp_question_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
