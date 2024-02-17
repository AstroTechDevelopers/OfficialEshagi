<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Representative;
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
        if(auth()->user()->utype=='Representative'){
			$rep=Representative::where('natid', auth()->user()->natid)->first();
			$partnerid = $rep->partner_id;
		}else if(auth()->user()->utype=='Partner'){
			$partner = Partner::where('regNumber', auth()->user()->natid)->first();
			$partnerid = $partner->id;
		}
		
		$category = Category::where('category_name', $row[10])->first();
		
		if(!empty($row[1])) {
			return new Product([
			    'loandevice' => @$row[0],
                'creator' => auth()->user()->name,
                'pcode' => @$row[1],
                'serial' => @$row[4],
                'pname' => @$row[5],
                'model' => @$row[3],
                'descrip' => @$row[7],
                'price' => @$row[8],
				'partner_id' => $partnerid,
				'product_category_id' => $category->id,
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
