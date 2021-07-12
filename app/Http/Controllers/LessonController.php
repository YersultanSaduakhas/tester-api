<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Http\Request;

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
    public function index()
    {
        return Lesson::all();
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
        return Lesson::create([
            'name'=>$request->input('name'),
            'question_count'=>$request->input('question_count'),
            'question_count_to_test'=>$request->input('question_count_to_test'),
            'language'=>$request->input('language')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        //
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
        $existingLesson = Lesson::find($id);
        if ($result) { 
            $existingLesson->update([
                'name'=>$request->input('name'),
                'question_count'=>$request->input('question_count'),
                'question_count_to_test'=>$request->input('question_count_to_test'),
                'language'=>$request->input('language')
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
        $existingLesson = Lesson::find($id);
        if ($result) { 
            $questionCount = Question::where('lesson_id',$id)->count();
            if($questionCount>0){
                return response([
                    'message' =>'Invalid credentials'
                ],Response::HTTP_INTERNAL_SERVER_ERROR );    
            }else{
                $existingLesson->delete();
                return response([
                    'message' =>'successfully deleted'
                ]);    
            }
        }else{
            return response([
                'message' =>'Invalid credentials'
            ],Response::HTTP_NOT_FOUND );
        }
    }
}
