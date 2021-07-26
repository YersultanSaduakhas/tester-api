<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Models\Question;
use App\Imports\BukStudentsImport;

class ImportExcelController extends Controller
{
    function index()
    {
     $data = DB::table('questions')->orderBy('id', 'DESC')->get();
     return $data;
    }

    function import(Request $request)
    {
     $this->validate($request, [
      'select_file'  => 'required|mimes:xls,xlsx'
     ]);

     $path = $request->file('select_file')->getRealPath();

    //  $data = Excel::import($path)->get();
     $filenaeeee = $request->file('select_file');
     Excel::import(new BukStudentsImport(),  $filenaeeee);
    //  $counter = 1;
    //  if($data->count() > 0)
    //  {
    //   foreach($data->toArray() as $key => $value)
    //   {
    //    foreach($value as $row)
    //    {
    //     $insert_data[] = array(
    //      'text'  => $row['text'],
    //      'option_1'   => $row['option_1'],
    //      'option_2'   => $row['option_2'],
    //      'option_3'   => $row['option_3'],
    //      'option_4'   => $row['option_4'],
    //      'option_5'   => $row['option_5'],
    //      'answer'   => intval(str_replace("option_", "", $row['answer'])),
    //      'reason'    => $row['reason'],
    //      'hint'  => $row['hint'],
    //      'tmp'   => 1
    //     );
    //    }
    //    break;// only first sheet upload
    //   }

    //   Question::where('tmp', 1)->delete();
    //   if(!empty($insert_data))
    //   {

    //    DB::table('questions')->insert($insert_data);
    //   }
    //  }
     return back()->with('success', 'Excel Data Imported successfully.');
    }
}