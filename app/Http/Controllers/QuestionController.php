<?php

namespace App\Http\Controllers;

use App\Models\Question;
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
        
        $questions = Question::when($searchText, function ($query, $searchText) {
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
            return Question::create([
                'lesson_id'=>$request->input('lesson_id'),
                'text'=>$request->input('text'),
                'option_1'=>$request->input('option_1'),
                'option_2'=>$request->input('option_2'),
                'option_3'=>$request->input('option_3'),
                'option_4'=>$request->input('option_4'),
                'option_5'=>$request->input('option_5'),
                'answer'=>$request->input('answer'),
                'reason'=>$request->input('reason'),
                'hint'=>$request->input('hint'),
                'tmp'=>$request->input('tmp')
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
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show( $questionId)
    {
        return Question::where('id',$questionId)->first();
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
                'option_1'=>$request->input('option_1'),
                'option_2'=>$request->input('option_2'),
                'option_3'=>$request->input('option_3'),
                'option_4'=>$request->input('option_4'),
                'option_5'=>$request->input('option_5'),
                'answer'=>$request->input('answer'),
                'reason'=>$request->input('reason'),
                'hint'=>$request->input('hint')
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
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingQuestion = Question::find($id);
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
