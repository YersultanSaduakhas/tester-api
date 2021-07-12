<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use Faker\Factory;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Factory::create();
        Lesson::truncate();
        Lesson::create([
            'id'=>1,
            'name'=>"Математика",
            'question_count'=>51,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);
        
        Lesson::create([
            'id'=>2,
            'name'=>"Қазақ тілі",
            'question_count'=>45,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);

        Lesson::create([
            'id'=>3,
            'name'=>"Қазақстан тарихы",
            'question_count'=>80,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);


        Lesson::create([
            'id'=>4,
            'name'=>"Орыс тілі",
            'question_count'=>68,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);


        Lesson::create([
            'id'=>5,
            'name'=>"Ағылшын тілі",
            'question_count'=>45,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);


        Lesson::create([
            'id'=>6,
            'name'=>"Биология",
            'question_count'=>97,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);


        Lesson::create([
            'id'=>7,
            'name'=>"Физика",
            'question_count'=>100,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);


        Lesson::create([
            'id'=>8,
            'name'=>"Химия",
            'question_count'=>151,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);


        Lesson::create([
            'id'=>9,
            'name'=>"География",
            'question_count'=>41,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);

        Lesson::create([
            'id'=>10,
            'name'=>"Информатика",
            'question_count'=>41,
            'question_count_to_test'=>40,
            'language'=>'kz'
        ]);

        Lesson::create([
            'id'=>11,
            'name'=>"Математика",
            'question_count'=>51,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);
        
        Lesson::create([
            'id'=>12,
            'name'=>"Казахский язык",
            'question_count'=>45,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);

        Lesson::create([
            'id'=>13,
            'name'=>"История Казахстана",
            'question_count'=>80,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);


        Lesson::create([
            'id'=>14,
            'name'=>"Русский язык",
            'question_count'=>68,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);


        Lesson::create([
            'id'=>15,
            'name'=>"Английский язык",
            'question_count'=>45,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);


        Lesson::create([
            'id'=>16,
            'name'=>"Биология",
            'question_count'=>97,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);


        Lesson::create([
            'id'=>17,
            'name'=>"Физика",
            'question_count'=>100,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);


        Lesson::create([
            'id'=>18,
            'name'=>"Химия",
            'question_count'=>151,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);


        Lesson::create([
            'id'=>19,
            'name'=>"География",
            'question_count'=>41,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);

        Lesson::create([
            'id'=>20,
            'name'=>"Информатика",
            'question_count'=>41,
            'question_count_to_test'=>40,
            'language'=>'ru'
        ]);
    }
}
