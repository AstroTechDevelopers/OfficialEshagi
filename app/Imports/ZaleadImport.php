<?php

namespace App\Imports;

use App\Models\Zalead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ZaleadImport implements ToModel , WithBatchInserts , WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if(!empty($row[0])) {
            return new Zalead([
                'nrc' => @$row[0],
                'business' => @$row[1],
                'first_name' => @$row[2],
                'last_name' => @$row[3],
                'mobile' => @$row[4],
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

