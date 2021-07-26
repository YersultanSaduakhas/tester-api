<?php

namespace App\Imports;

use App\StudentRegistration;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BukStudentsImport implements ToCollection
{

    // public function  __construct($data)
    // {
    //     // dd($cartage);
    //     $this->data = $data;
    // }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {

        foreach ($collection as $row) {

         $counter = 1;
         if($data->count() > 0)
         {
          foreach($data->toArray() as $key => $value)
          {
           foreach($value as $row)
           {
            $insert_data[] = array(
             'text'  => $row['text'],
             'option_1'   => $row['option_1'],
             'option_2'   => $row['option_2'],
             'option_3'   => $row['option_3'],
             'option_4'   => $row['option_4'],
             'option_5'   => $row['option_5'],
             'answer'   => intval(str_replace("option_", "", $row['answer'])),
             'reason'    => $row['reason'],
             'hint'  => $row['hint'],
             'tmp'   => 1
            );
           }
           break;// only first sheet upload
          }

          Question::where('tmp', 1)->delete();
          if(!empty($insert_data))
          {

           DB::table('questions')->insert($insert_data);
          }
         }
            
         $addstudent = new StudentRegistration([
                'login_user_id' => auth()->id(),
                'school_id' => $this->data['school_id'],
                'class_id' => $this->data['class_id'],
                'section_id' => $this->data['section_id'],
                'stud_name' => $row[0],
                'stud_email' => "-",
                'stud_phno' => 123,
                'stud_type' => "-",
                'stud_father' => "-",
                'stud_mother' => "-",
                'stud_id' => $row[1],
                'stud_status' => "active",
            ]);

             $addstudent->save();

            dd("STOP inserted");

            $addstudent->user()->create(
                ['name' => $row[0],
                    'username' => $row[1],
                    'user_type' => "student",
                    'password' => $row[2],
                    'status' => "active",
                ]);

        
        }
    }
}