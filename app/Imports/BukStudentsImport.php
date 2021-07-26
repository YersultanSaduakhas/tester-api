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