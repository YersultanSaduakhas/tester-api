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

class LessonController extends Controller
{
    public function getByLang($lang){
        return Lesson::where('language', $lang)->get();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang = null;
        $langVal =  $request->query('lang');
        if(isset($langVal)){
            $lang = $langVal;
        }

        $onlyAdditional = null;
        $onlyAdditionalVal =  $request->query('only_additional');
        if(isset($onlyAdditionalVal)){
            $onlyAdditional = $onlyAdditionalVal;
        }

        $lessons = Lesson::with('cross_lessons')->when($langVal, function ($query, $langVal) {
            return $query->where('language',  $langVal );
        })->when($onlyAdditionalVal, function ($query, $onlyAdditionalVal) {
            return $query->whereNotIn('l_type', ['math','qazaq_tili','history']);
        })->orderBy('id', 'ASC')->get();
        // $data = DB::table('questions')->orderBy('id', 'DESC')->get();
        return $lessons;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return [
            'name'=>'',
            'question_count'=>0,
            'question_count_to_test'=>0,
            'language'=>'kz'
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->isAdmin()){
            $newLesson = Lesson::create([
                'l_type'=>$request->input('l_type'),
                'name'=>$request->input('name'),
                'question_count'=>$request->input('question_count'),
                'question_count_to_test'=>$request->input('question_count_to_test'),
                'language'=>$request->input('language')
            ]);
            
            $cross_lessons = $request->input('cross_lessons');
            if(isset($cross_lessons)){
                foreach ($cross_lessons as $lesson_) {
                    CrossLesson::create([
                        'lesson_id'=>$newLesson->id,
                        'cross_lesson_id'=>$lesson_['id']
                    ]);    
                }
            }

            Question::where('lesson_id', -1)->where('tmp',1)
            ->update([
                'lesson_id' =>  $newLesson->id,
                'tmp' =>  0,
            ]);    
        }else{
            return response([
                'message' =>'Invalid credentials'
            ],Response::HTTP_UNAUTHORIZED );
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show($lessonId)
    {
        return Lesson::with('cross_lessons')->where('id',$lessonId)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($this->isAdmin()==false){
            return response([
                'message' =>'Invalid user'
            ],403 );
        }
        $existingLesson = Lesson::find($id);
        if ($existingLesson) { 
            $existingLesson->update([
                'l_type'=>$request->input('l_type'),
                'name'=>$request->input('name'),
                'question_count'=>$request->input('question_count'),
                'question_count_to_test'=>$request->input('question_count_to_test'),
                'language'=>$request->input('language')
            ]);

            CrossLesson::where('lesson_id',$existingLesson->id)->delete();
            $cross_lessons = $request->input('cross_lessons');
            if(isset($cross_lessons)){
                foreach ($cross_lessons as $lesson_) {
                    CrossLesson::create([
                        'lesson_id'=>$existingLesson->id,
                        'cross_lesson_id'=>$existingLesson['id']
                    ]);    
                }
            }

            $questionOperation = $request->input('q_operation');
            if($questionOperation==='new'){
                
                $questions = Question::where('lesson_id', $existingLesson->lesson_id)->all();
                foreach ($questions as $question) {
                    Option::where('question_id', $question->id)->delete();
                }
                Question::where('lesson_id', $existingLesson->lesson_id)->delete();
                Question::where('lesson_id', -1)
                ->update([
                    'lesson_id' =>  $existingLesson->id,
                    'tmp' =>  0,
                ]);
            }
            else if($questionOperation==='merge'){
                Question::where('lesson_id', -1)
                ->update([
                    'lesson_id' =>  $existingLesson->id,
                    'tmp' =>  0,
                ]);
            }
            else if($questionOperation==='no_touch'){
                
            }

            $existingLesson->update([
                'question_count'=>Question::where('lesson_id', $existingLesson->id)->count()
            ]);

            return response([
                'message' =>'successfully updated'
            ]);    
            
        }else{
            return response([
                'message' =>'Invalid credentials'
            ],Response::HTTP_NOT_FOUND );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id==-1){
            Question::where('lesson_id', -1)->delete();
            return response([
                'message' =>'successfully deleted'
            ]);    
        }
        $existingLesson = Lesson::find($id);
        if ($existingLesson) { 
            CrossLesson::where('lesson_id',$id)->delete();
            $questionCount = Question::where('lesson_id',$id)->count();
            if($questionCount>0){
                $questions = Question::where('lesson_id', $id)->all();
                foreach ($questions as $question) {
                    Option::where('question_id', $question->id)->delete();
                }
                Question::where('lesson_id', $id)->delete();
            }
            $existingLesson->delete();
            return response([
                'message' =>'successfully deleted'
            ]);    
        }else{
            return response([
                'message' =>'Invalid credentials'
            ],Response::HTTP_NOT_FOUND );
        }
    }

    private function isAdmin(){
        $adminUserName=env('APP_ADMIN_USER_NAME', null);
        $res = Auth::user();
        $isAdmin = isset($adminUserName)&&$res->email===$adminUserName;
        return $isAdmin;
    }
}
