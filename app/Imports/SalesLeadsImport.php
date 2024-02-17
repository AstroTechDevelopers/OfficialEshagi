<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SalesLeadsImport implements ToModel , WithBatchInserts , WithChunkReading
{
    /**
    * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!empty($row[8])) {
            return new Lead([
                'name' => substr(@$row[1],0, 1).@$row[2],
                'first_name' => @$row[1],
                'last_name' => @$row[2],
                'email' => @$row[3],
                'natid' => @$row[4],
                'mobile' => @$row[5],
                'password' => @$row[6],
                'token' => @$row[7],
                'ecnumber' => @$row[8],
                'address' => @$row[9],
                'locale' => @$row[10],
            ]);
        } else
            return null;
    }

    public function batchSize(): int
    {
        return 5000;
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}
