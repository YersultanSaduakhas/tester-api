<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $isTmp = null;
        $isTmpVal =  $request->query('is_tmp');
        if(isset($isTmpVal)){
            $isTmp = $isTmpVal;
        }

        $lessondId = null;
        $lessondIdVal =  $request->query('lesson_id');
        if(isset($lessondIdVal)){
            $lessondId = $lessondIdVal;
        }
        
        $size = 15;
        $sizeVal = $request->query('size');
        if(isset($sizeVal)){
            $size = $request->query('size');
        }
        $searchText = null;
        $searchTextVal = $request->query('search_text');

        if(isset($searchTextVal)){
            $searchText = $request->query('search_text');
        }
        
        $questions = Question::with('options')->when($searchText, function ($query, $searchText) {
            return $query->where('text', 'like', '%' . $searchText . '%');
        })->when($isTmp, function ($query, $isTmp) {
            return $query->where('tmp', 1);
        })->when($lessondId, function ($query, $lessondId) {
            return $query->where('lesson_id', $lessondId);
        })->orderBy('id', 'ASC')->paginate($size);
        // $data = DB::table('questions')->orderBy('id', 'DESC')->get();
        return $questions;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $question = Question::create([
                'lesson_id'=>$request->input('lesson_id'),
                'text'=>$request->input('text'),
                'answers'=>$request->input('answers'),
                'reason'=>$request->input('reason') ?? '',
                'hint'=>$request->input('hint') ?? '',
                'tmp'=>$request->input('tmp'),
                'is_5_optioned'=>count($request->input('options')) === 5 ? 1: 0,
                'right_answer_count'=>$request->input('right_answer_count')
            ]);    
            $options = $request->input('options');
            foreach ($options as $option) {
                Option::create([
                    'question_id'=>$question->id,
                    'text'=>$option['text'],
                    'is_right'=>$option['is_right']
                ]);    
            }
            return Question::with('options')->find($question->id);
        }else{
            return response([
                'message' =>'Invalid credentials'
            ],Response::HTTP_UNAUTHORIZED );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        return Question::with('options')->where('id',$id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($this->isAdmin()==false){
            return response([
                'message' =>'Invalid user'
            ],403 );
        }
        $existingQuestion = Question::find($id);
        if ($existingQuestion) { 
            $existingQuestion->update([
                'text'=>$request->input('text'),
                'answers'=>$request->input('answers'),
                'reason'=>$request->input('reason') ?? '',
                'hint'=>$request->input('hint') ?? '',
                'is_5_optioned'=>count($request->input('options')) === 5 ? 1: 0,
                'right_answer_count'=>$request->input('right_answer_count')
            ]);
            Option::where('question_id', $existingQuestion->id)->delete();
            $options = $request->get('options');
            foreach ($options as $option) {
                Option::create([
                    'question_id'=>$existingQuestion->id,
                    'text'=>$option['text'],
                    'is_right'=>$option['is_right']
                ]);    
            }
            
            return response([
                'data'=>Question::with('options')->find($existingQuestion->id),
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
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingQuestion = Question::find($id);
        Option::where('question_id', $id)->delete();
        if ($existingQuestion) { 
            $existingQuestion->delete();
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
