<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class ZambiaDisbursedLoansExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('zambia_loans as l')
            ->join('zambians as c', function($join) {
                $join->on('c.id', '=', 'l.zambian_id');
            })
            ->select('l.created_at','c.creator','c.first_name','c.last_name','c.nrc','l.loan_principal_amount','l.loan_duration')
            ->whereDate('l.disbursed_at', Carbon::today())
            ->where('l.isDisbursed','=', true)
            ->where('l.deleted_at','=', null)
            ->get();
    }
}
