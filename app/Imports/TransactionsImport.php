<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TransactionsImport implements ToModel, WithMultipleSheets,WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
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
        return new Question([
            'lesson_id'=>-1,
            'text'  => $row[0],
            'option_1'   => $row[1],
            'option_2'   => $row[2],
            'option_3'   => $row[3],
            'option_4'   => $row[4],
            'option_5'   => $row[5],
            'answer'   => intval(str_replace("option_", "", $row[6])),
            'reason'    => isset($row[7])?$row[7]:"",
            'hint'  => isset($row[8])?$row[8]:"",
            'tmp'   => 1
        ]);

    }
}
