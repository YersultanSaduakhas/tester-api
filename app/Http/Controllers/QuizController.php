<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\CrossLesson;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{

    public function createRandomQuizQuestion(Request $request){
        
        $lang = $request->input('language');
        $profileLesson_1_id = $request->input('profile_lesson_1_id');
        $profileLesson_2_id = $request->input('profile_lesson_2_id');
        if(!isset($profileLesson_1_id)||!isset($profileLesson_2_id)||!isset($lang)){
            return response([
                'message' =>'input parameters not correct',
            ],500);
        }

        $lessons = [
            $array = [
                "name"=>'',
                "question_ids"=>[],
                "lesson_id"=>-1,
                "key"=>'math',
                "question_count" => 15,
                "type"=>'main_l'
            ],
            $array = [
                "name"=>'',
                "question_ids"=>[],
                "lesson_id"=>-1,
                "key"=>'qazaq_tili',
                "question_count" => 20,
                "type"=>'main_l'
            ],
            $array = [
                "name"=>'',
                "question_ids"=>[],
                "lesson_id"=>-1,
                "key"=>'history',
                "question_count" => 15,
                "type"=>'main_l'
            ],
            $array = [
                "name"=>'',
                "question_ids"=>[],
                "key"=>'profile_1',
                "question_count" => 25,
                "question_count_2" => 10,
                "type"=>'profile',
                "lesson_id"=>$profileLesson_1_id,
                "no_5_optioned_question_ids"=>[]
            ],
            $array = [
                "name"=>'',
                "question_ids"=>[],
                "key"=>'profile_2',
                "question_count" => 25,
                "question_count_2" => 10,
                "type"=>'profile',
                "lesson_id"=>$profileLesson_2_id,
                "no_5_optioned_question_ids"=>[]
            ]
        ];

        foreach ( $lessons as $key=>$value ){
            $lesson_ = null;            
            if($lessons[$key]['type'] === 'main_l'){
                $lesson_ = Lesson::where('l_type', $lessons[$key]['key'])->where('language',$lang)->first();
            }
            else{
                $lesson_ = Lesson::where('id', $lessons[$key]['lesson_id'])->first();
                if(isset($lesson_)){
                    $answer_count_3_questions = Question::select('id')
                    ->where('lesson_id',$lesson_->id)
                    ->where('is_5_optioned',0)
                    ->where('right_answer_count',3)->get();
                    $answer_count_3_questions = $this->getIdArray($answer_count_3_questions);

                    $answer_count_2_questions = Question::select('id')
                    ->where('lesson_id',$lesson_->id)
                    ->where('is_5_optioned',0)
                    ->where('right_answer_count',2)->get();
                    $answer_count_2_questions = $this->getIdArray($answer_count_2_questions);

                    $answer_count_1_questions = Question::select('id')
                    ->where('lesson_id',$lesson_->id)
                    ->where('is_5_optioned',0)
                    ->where('right_answer_count',1)->get();
                    $answer_count_1_questions = $this->getIdArray($answer_count_1_questions);

                    $no_5_optioned_questions = [
                        "one" => $array = [
                            "question_count" => 3,
                            "question_ids"=>$answer_count_1_questions
                        ],
                        "two" => $array = [
                            "question_count" => 4,
                            "question_ids"=>$answer_count_2_questions
                        ],
                        "three" => $array = [
                            "question_count" => 3,
                            "question_ids"=>$answer_count_3_questions
                        ]
                    ];
                    foreach ( $no_5_optioned_questions as $key_ => $value_ ){
                        if(isset($value_['question_ids'])&&count($value_['question_ids'])>0){
                            if(count($value_['question_ids'])<=$value_['question_count']){
                                if(count($lessons[$key]['no_5_optioned_question_ids'])==0){
                                    $lessons[$key]['no_5_optioned_question_ids'] = $value_['question_ids'];
                                }else{
                                    $lessons[$key]['no_5_optioned_question_ids'] = array_merge($lessons[$key]['no_5_optioned_question_ids'],$value_['question_ids']);
                                }
                            }
                            else{
                                $rand_id_keys = array_rand($value_['question_ids'], $value_['question_count']);  
                                foreach ($rand_id_keys as $question_key) {
                                    array_push($lessons[$key]['no_5_optioned_question_ids'],$value_['question_ids'][$question_key]);
                                }
                            }
                        }
                    }
                    sort($lessons[$key]['no_5_optioned_question_ids']);
                }  
            }
            if(isset($lesson_)){
                $lessons[$key]['name'] = $lesson_['name'];
                $lessons[$key]['lesson_id'] = $lesson_['id'];
                $questionsIds_ = Question::select('id')
                ->where('lesson_id',$lesson_->id)
                ->where('is_5_optioned',1)
                ->where('right_answer_count',1)->get();
                $questionsIds_ = $this->getIdArray($questionsIds_);
                if(isset($questionsIds_)){
                    $questionsIdsRes_ = array();
                    if(count($questionsIds_)<=$lessons[$key]['question_count']){
                        $questionsIdsRes_ = $questionsIds_; 
                    }else{
                        $rand_math_keys = array_rand($questionsIds_, $lessons[$key]['question_count']);  
                        foreach ($rand_math_keys as $question_key) {
                            array_push($questionsIdsRes_,$questionsIds_[$question_key]);
                        }
                    }
                    $lessons[$key]['question_ids'] = $questionsIdsRes_;
                    sort($lessons[$key]['question_ids']);
                }
            }
        }

        

        return $lessons;

    }

    private function getIdArray($objArr){
        $res = array();
        if(isset($objArr)){
            foreach ( $objArr as $key => $value ){
                array_push($res,$value->id);
            }
        }
        return $res;
    }

    private function isAdmin(){
        $adminUserName=env('APP_ADMIN_USER_NAME', null);
        $res = Auth::user();
        $isAdmin = isset($adminUserName)&&$res->email===$adminUserName;
        return $isAdmin;
    }
}
