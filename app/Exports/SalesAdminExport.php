<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 23/2/2021
 * Time: 00:52
 */


namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class SalesAdminExport implements FromCollection, ShouldAutoSize, WithHeadings
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
            ->select('c.creator','c.first_name','c.last_name','c.natid',
                DB::raw('(CASE WHEN l.loan_type = 1 THEN "Store Credit" WHEN l.loan_type = 2 THEN "Cash Loan" WHEN l.loan_type = 3 THEN "Recharge Credit" WHEN l.loan_type = 4 THEN "Hybrid Loan" ELSE 0 END) AS loan_type'),
                DB::raw('(CASE WHEN l.loan_status = 0 THEN "Unsigned" WHEN l.loan_status = 1 THEN "New Loan" WHEN l.loan_status = 2 THEN "KYC CBZ (PRIVATE)" WHEN l.loan_status = 3 THEN "Stop Order(PRIVATE)" WHEN l.loan_status = 4 THEN "MOU(PRIVATE)" WHEN l.loan_status = 5 THEN "Client Bank(PRIVATE)" WHEN l.loan_status = 6 THEN "HR(PRIVATE)" WHEN l.loan_status = 7 THEN "CBZ Banking(PRIVATE)" WHEN l.loan_status = 8 THEN "RedSphere Processing(PRIVATE)" WHEN l.loan_status = 9 THEN "CBZ KYC(GOVT)" WHEN l.loan_status = 10 THEN "Ndasenda(GOVT)" WHEN l.loan_status = 11 THEN "CBZ Banking(GOVT)" WHEN l.loan_status = 12 THEN "Disbursed" WHEN l.loan_status = 13 THEN "Declined" WHEN l.loan_status = 14 THEN "Paid Back" ELSE 100 END) AS loan_status'),
                'l.amount','l.paybackPeriod')
            ->whereDate('l.created_at', Carbon::today())
            ->where('l.deleted_at','=', null)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Agent',
            'First Name',
            'Last Name',
            'National ID',
            'Loan Type',
            'Loan Status',
            'Amount',
            'Tenure',
        ];
    }
}
