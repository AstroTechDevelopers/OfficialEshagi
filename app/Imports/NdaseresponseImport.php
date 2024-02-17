<?php

namespace App\Imports;

use App\Models\Ndaseresponse;
use Maatwebsite\Excel\Concerns\ToModel;

class NdaseresponseImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!empty($row[0])) {
            return new Ndaseresponse([
                'recid' => @$row[0],
                'deductioncode' => @$row[1],
                'reference' => @$row[2],
                'idnumber' => @$row[3],
                'ecnumber' => @$row[4],
                'type' => @$row[5],
                'status' => @$row[6],
                'startdate' => @$row[7],
                'enddate' => @$row[8],
                'amount' => @$row[9],
                'total' => @$row[10],
                'surname' => @$row[11],
                'bank' => @$row[12],
                'bankacc' => @$row[13],
                'message' => @$row[14]
            ]);
        } else
            return null;
    }
}
