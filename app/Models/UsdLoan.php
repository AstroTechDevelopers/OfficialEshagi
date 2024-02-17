<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsdLoan extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usd_loans';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that are hidden.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'disbursed_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'partner_id',
        'channel_id',
        'funder_id',
        'funder_acc_number',
        'loan_number',
        'loan_type', // 1: Store Credit; 2: Cash Loan;
        'loan_status', // 0: Not Signed; 1: New; 2: KYC CBZ (PRIVATE); 3: Stop Order(PRIVATE); 4: MOU(PRIVATE); 5: Client Bank(PRIVATE); 6: HR(PRIVATE); 7:CBZ Banking(PRIVATE); 8:RedSphere Processing(PRIVATE); 9: CBZ KYC(GOVT); 10: Ndasenda(GOVT); 11: CBZ Banking(GOVT); 12: Disbursed; 13: Declined; 14: Paid Back; 15: CRB Check(Self-Finance - Local board); 16: eShagi KYC(Self-Finance); 17: Issue Loan/Device(Self-Finance: Acknowledge Payment); 18: In Repayment Phase(Self-Finance);
        'amount',
        'gross_amount',
        'tenure',
        'interestRate',
        'monthly',
        'ags_commission',
        'app_fee',
        'est_fee',
        'insurance',
        'charges',
        'dd_approval', //DEDUCTION APPROVAL
        'dd_approval_ref', //DEDUCTION APPROVAL Reference
        'disbursement_ref',
        'ndasendaBatch',
        'ndasendaRef1',
        'ndasendaRef2',
        'ndasendaState',
        'ndasendaMessage',
        'reds_loanstate',
        'isDisbursed',
        'notes',
        'locale',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'user_id'                              => 'integer',
        'client_id'                        => 'string',
        'partner_id'                         => 'string',
        'channel_id'                             => 'string',
        'funder_id'                             => 'string',
        'funder_acc_number'                             => 'string',
        'loan_number'                             => 'string',
        'loan_type'                             => 'string',
        'loan_status'                             => 'string',
        'amount'                             => 'double',
        'gross_amount'                             => 'double',
        'tenure'                             => 'string',
        'interestRate'                             => 'integer',
        'monthly'                             => 'double',
        'ags_commission'                             => 'double',
        'app_fee'                             => 'double',
        'est_fee'                             => 'double',
        'insurance'                             => 'double',
        'charges'                             => 'double',
        'dd_approval'                             => 'boolean',
        'dd_approval_ref'                             => 'string',
        'disbursement_ref'                             => 'string',
        'ndasendaBatch'                             => 'string',
        'ndasendaRef1'                             => 'string',
        'ndasendaRef2'                             => 'string',
        'ndasendaState'                             => 'string',
        'ndasendaMessage'                             => 'string',
        'reds_loanstate'                             => 'string',
        'isDisbursed'                             => 'boolean',
        'notes'                             => 'string',
        'locale'                             => 'string',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function usd_loan_record(){
        return $this->hasOne(UsdLoanRecord::class);
    }
}
