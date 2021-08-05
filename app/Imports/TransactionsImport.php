<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Option;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TransactionsImport implements ToModel, WithMultipleSheets, WithHeadingRow 
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 1;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $tmp_question_id =  uniqid('tmp_q_');
        $options = array();
        $anwers = $row['answers'];
        $anwers = str_replace(".", ",", $anwers);
        $integerAnswerOptionIds = array_map('intval', explode(',', $anwers));
        
        foreach ($row as $key => $value) {
            if(substr( $key, 0, 7 ) === 'option_' && isset($value)){
                $order = intval(explode("_", $key)[1]);
                Option::create([
                    'text'=>$value,
                    'question_id'=>-1,
                    'tmp_question_id'=>$tmp_question_id,
                    'is_right'=>in_array($order,$integerAnswerOptionIds)
                ]);
                array_push($options, $value);        
            }
            
        }
        return new Question([
            'lesson_id'=>-1,
            'text'  => $row['text'],
            'answers'   => $anwers,
            'reason'    => isset($row['reason'])?$row['reason']:"",
            'hint'  => isset($row['hint'])?$row['hint']:"",
            'tmp'   => 1,
            'tmp_question_id'=>$tmp_question_id,
            'is_5_optioned'=>count($options)==5
        ]);

    }
}
