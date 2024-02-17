<?php

namespace App\Imports;

use App\Models\Partner;
use App\Models\Representative;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SalesRepsImport implements ToModel , WithBatchInserts , WithChunkReading
{
    public function __construct(Partner $partner)
    {
        $this->partner_id = $partner->id;
        $this->partner_name = $partner->partner_name;
    }

    /**
     * @param array $row
     *
     * @param $partner
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if(!empty($row[5])) {
            return new Representative([
                'creator' => strtolower($this->partner_name),
                'first_name' => @$row[1],
                'last_name' => @$row[2],
                'email' => @$row[3],
                'natid' => @$row[4] ?? @$row[5],
                'mobile' => @$row[5],
                'partner_id' => $this->partner_id,
                'branch' => @$row[7],
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
