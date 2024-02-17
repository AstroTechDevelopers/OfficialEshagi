<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class ZambiaWeeklyStatusExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('zambia_loans as l')
            ->select(DB::raw('DATE(l.created_at) as date'),
                DB::raw('DAYNAME(l.created_at) as Day'),
                DB::raw('SUM(l.loan_principal_amount) as totalAmount'),'c.creator',
                DB::raw('(CASE WHEN l.loan_status = 1 THEN "Open" WHEN l.loan_status = 3 THEN "Defaulted" WHEN l.loan_status = 182376 THEN "Credit Counseling" WHEN l.loan_status = 182377 THEN "Collection Agency" WHEN l.loan_status = 182378 THEN "Sequestrate" WHEN l.loan_status = 182379 THEN "Debt Review" WHEN l.loan_status = 182380 THEN "Fraud" WHEN l.loan_status = 182381 THEN "Investigation" WHEN l.loan_status = 182382 THEN "Legal" WHEN l.loan_status = 182383 THEN "Write-Off" WHEN l.loan_status = 9 THEN "Denied" WHEN l.loan_status = 17 THEN "Not Taken Up" WHEN l.loan_status = 8 THEN "Processing" WHEN l.loan_status = 113 THEN "KYC Loan Officer" WHEN l.loan_status = 114 THEN "KYC Manager" WHEN l.loan_status = 115 THEN "Loan Disk Initiation" WHEN l.loan_status = 116 THEN "PayTrigger Enrollment" WHEN l.loan_status = 117 THEN "Enrolled on PayTrigger" WHEN l.loan_status = 118 THEN "Locked on PayTrigger" WHEN l.loan_status = 119 THEN "Waiting Deposit Payment" WHEN l.loan_status = 120 THEN "Device Released" ELSE 120 END) AS loan_status'),
            )
            ->join('zambians as c','c.id', '=', 'l.zambian_id')
            ->where(DB::raw('date(l.created_at)'), '>', DB::raw('DATE_SUB(NOW(), INTERVAL 1 WEEK)'))
            ->where(DB::raw('MONTH(l.created_at)'), '=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'), '=', DB::raw('YEAR(CURDATE())'))
            ->where('l.deleted_at','=', null)
            ->groupBy('l.loan_status','l.id' )
            ->orderBy('l.created_at', 'DESC')
            ->get();
    }
}
