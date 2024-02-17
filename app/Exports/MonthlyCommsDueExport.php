<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 23/2/2021
 * Time: 00:24
 */


namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;


class MonthlyCommsDueExport implements WithHeadings, FromCollection, ShouldAutoSize
{

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return DB::table('commissions as c')
            ->join('clients as cl', function($join) {
                $join->on('cl.id', '=', 'c.client');
            })
            ->select('c.id','c.agent','cl.first_name','cl.last_name','cl.natid','c.loanid','c.loanamt','c.commission')
            ->where(DB::raw('MONTH(c.created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(c.created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('c.paidout','=', false)
            ->where('c.deleted_at','=', null)
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Agent',
            'First Name',
            'Last Name',
            'National ID',
            'Loan ID',
            'Amount',
            'Commission',
        ];
    }
}
