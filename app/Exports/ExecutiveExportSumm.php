<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExecutiveExportSumm implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $dailySales = DB::table('loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average')
            )
            ->whereDate('created_at', Carbon::today())
            ->where('deleted_at','=', null)
            ->get();

        $monthToDateLoans = DB::table('loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->get();

        $yearToDateLoans = DB::table('loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->get();

        $disbursedMonthToDateLoans = DB::table('loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('loan_status','=', 12)
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->get();

        $cumLoans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.id','l.created_at','c.first_name','c.last_name','c.creator','c.natid','l.loan_type','l.interestRate','l.amount','l.monthly','l.paybackPeriod')
            ->where(DB::raw('MONTH(l.created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('l.loan_status','=', 12)
            ->where('l.deleted_at','=', null)
            ->get();

        $sum = 0;
        foreach ($cumLoans as $loan){
            for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                $sum += $loanInts;
            }
        }

        $usdLoans = DB::table('usd_loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.id','l.created_at','c.first_name','c.last_name','c.creator','c.natid','l.loan_type','l.interestRate','l.amount','l.monthly','l.tenure')
            ->where(DB::raw('MONTH(l.created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('l.loan_status','=', 12)
            ->where('l.deleted_at','=', null)
            ->get();

        $usdSum = 0;
        foreach ($usdLoans as $loan){
            for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                $usdSum += $loanInts;
            }
        }

        $devLoans = DB::table('device_loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.id','l.created_at','c.first_name','c.last_name','c.creator','c.natid','l.loan_type','l.interestRate','l.amount','l.monthly','l.paybackPeriod')
            ->where(DB::raw('MONTH(l.created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('l.loan_status','=', 12)
            ->where('l.deleted_at','=', null)
            ->get();

        $devSum = 0;
        foreach ($devLoans as $loan){
            for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                $devSum += $loanInts;
            }
        }

        $zamLoans = DB::table('usd_loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.id','l.created_at','c.first_name','c.last_name','c.creator','c.natid','l.loan_type','l.interestRate','l.amount','l.monthly','l.tenure')
            ->where(DB::raw('MONTH(l.created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('l.loan_status','=', 12)
            ->where('l.deleted_at','=', null)
            ->get();

        $zamSum = 0;
        foreach ($zamLoans as $loan){
            for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                $zamSum += $loanInts;
            }
        }

        return view('reports.executive-template', [
            'dailySales' => $dailySales,
            'monthToDateLoans' => $monthToDateLoans,
            'yearToDateLoans' => $yearToDateLoans,
            'disbursedMonthToDateLoans' => $disbursedMonthToDateLoans,
            'cumLoans' => $cumLoans,
            'sum' => $sum,
            'usdLoans' => $usdLoans,
            'usdSum' => $usdSum,
            'devLoans' => $devLoans,
            'devSum' => $devSum,
            'zamLoans' => $zamLoans,
            'zamSum' => $zamSum,
        ]);
    }
}
