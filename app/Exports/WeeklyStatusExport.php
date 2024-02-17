<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 22/2/2021
 * Time: 23:07
 */


namespace App\Exports;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WeeklyStatusExport implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithHeadings
{

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return DB::table('loans as l')
            ->select(DB::raw('DATE(l.created_at) as date'),
                DB::raw('DAYNAME(l.created_at) as Day'),
                DB::raw('SUM(l.amount) as totalAmount'),'c.creator',
                DB::raw('(CASE WHEN l.loan_status = 0 THEN "Unsigned" WHEN l.loan_status = 1 THEN "New Loan" WHEN l.loan_status = 2 THEN "KYC CBZ (PRIVATE)" WHEN l.loan_status = 3 THEN "Stop Order(PRIVATE)" WHEN l.loan_status = 4 THEN "MOU(PRIVATE)" WHEN l.loan_status = 5 THEN "Client Bank(PRIVATE)" WHEN l.loan_status = 6 THEN "HR(PRIVATE)" WHEN l.loan_status = 7 THEN "CBZ Banking(PRIVATE)" WHEN l.loan_status = 8 THEN "RedSphere Processing(PRIVATE)" WHEN l.loan_status = 9 THEN "CBZ KYC(GOVT)" WHEN l.loan_status = 10 THEN "Ndasenda(GOVT)" WHEN l.loan_status = 11 THEN "CBZ Banking(GOVT)" WHEN l.loan_status = 12 THEN "Disbursed" WHEN l.loan_status = 13 THEN "Declined" WHEN l.loan_status = 14 THEN "Paid Back" ELSE 100 END) AS loan_status'),
                DB::raw('(CASE WHEN l.loan_type = 1 THEN "Store Credit" WHEN l.loan_type = 2 THEN "Cash Loan" WHEN l.loan_type = 3 THEN "Recharge Credit" WHEN l.loan_type = 4 THEN "Hybrid Loan" ELSE 0 END) AS loan_type')
            )
            ->join('clients as c','c.id', '=', 'l.client_id')
            ->where(DB::raw('date(l.created_at)'), '>', DB::raw('DATE_SUB(NOW(), INTERVAL 1 WEEK)'))
            ->where(DB::raw('MONTH(l.created_at)'), '=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'), '=', DB::raw('YEAR(CURDATE())'))
            ->where('l.locale','=', 1)
            ->where('l.deleted_at','=', null)
            ->groupBy('l.loan_status','l.id' )
            ->orderBy('l.created_at', 'DESC')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Applied On',
            'Day',
            'Amount',
            'Agent',
            'Loan Status',
            'Loan Type',
        ];
    }
}
