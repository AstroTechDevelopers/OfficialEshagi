<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MerchantLoanExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $loans = DB::table('loans as l')
            ->join('users as u', function($join) {
                $join->on('l.partner_id', '=', 'u.id');
            })
            ->join('clients as c', function($join) {
                $join->on('l.client_id', '=', 'c.id');
            })
            ->select('l.created_at','u.first_name as merchant','c.first_name','c.last_name','c.natid',DB::raw('(CASE WHEN l.loan_type = 1 THEN "Store Credit" WHEN l.loan_type = 2 THEN "Cash Loan" WHEN l.loan_type = 3 THEN "Recharge Credit" WHEN l.loan_type = 4 THEN "Hybrid Loan" ELSE 0 END) AS loan_type'),'l.amount','l.paybackPeriod')
            ->whereDate('l.created_at', Carbon::today())
            ->where('u.utype','=', 'Partner')
            ->where('l.deleted_at','=', null)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Applied On',
            'Merchant',
            'First Name',
            'Last Name',
            'National ID',
            'Loan Type',
            'Amount',
            'Tenure',
        ];
    }
}
