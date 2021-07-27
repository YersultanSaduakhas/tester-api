<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Models\Question;
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
        Question::where('tmp', 1)->delete();
        \Excel::import(new TransactionsImport,$request->select_file);
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