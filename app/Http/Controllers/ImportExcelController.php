<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Models\Question;
use App\Models\Option;
use App\Imports\TransactionsImport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ImportExcelController extends Controller
{
    function index(Request $request)
    {
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
        })->orderBy('id', 'ASC')->paginate($size);
        // $data = DB::table('questions')->orderBy('id', 'DESC')->get();
        return $questions;
    }

    function import(Request $request)
    {
        $questions = Question::where('tmp', 1)->orWhere('lesson_id', -1)->get();
        foreach ($questions as $question) {
            Option::where('tmp_question_id', $question->tmp_question_id)
            ->delete(); 
        }
        Question::where('tmp', 1)->orWhere('lesson_id', -1)->delete();
        \Excel::import(new TransactionsImport,$request->select_file);
        //updating tmp question options
        $questions = Question::where('tmp', 1)->get();
        foreach ($questions as $question) {
            Option::where('tmp_question_id', $question->tmp_question_id)
            ->update([
                'question_id' =>  $question->id
            ]); 
        }

        return response([
            'message' =>'Excel Data Imported successfully'
        ]);
    }

    private function isAdmin(){
        $adminUserName=env('APP_ADMIN_USER_NAME', null);
        $res = Auth::user();
        $isAdmin = isset($adminUserName)&&$res->email===$adminUserName;
        return $isAdmin;
    }
}