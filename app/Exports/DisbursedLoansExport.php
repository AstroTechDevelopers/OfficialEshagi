<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 22/2/2021
 * Time: 22:41
 */


namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class DisbursedLoansExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->join('commissions as m', function($join) {
                $join->on('l.id', '=', 'm.loanid');
            })
            ->select('l.created_at','c.creator','c.first_name','c.last_name','c.natid',DB::raw('(CASE WHEN l.loan_type = 1 THEN "Store Credit" WHEN l.loan_type = 2 THEN "Cash Loan" WHEN l.loan_type = 3 THEN "Recharge Credit" WHEN l.loan_type = 4 THEN "Hybrid Loan" ELSE 0 END) AS loan_type'),'l.amount','l.paybackPeriod')
            ->whereDate('m.created_at', Carbon::today())
            ->where('l.loan_status','=', 12)
            ->where('l.deleted_at','=', null)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Applied On',
            'Agent',
            'First Name',
            'Last Name',
            'National ID',
            'Loan Type',
            'Amount',
            'Tenure',
        ];
    }
}
