<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements ToModel , WithBatchInserts , WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if(!empty($row[1])) {
            return new Product([
                'creator' => auth()->user()->name,
                'pcode' => @$row[0],
                'serial' => @$row[1],
                'pname' => @$row[2],
                'model' => @$row[3],
                'descrip' => @$row[4],
                'price' => @$row[5],
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
