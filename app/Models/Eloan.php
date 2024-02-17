<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eloan extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'eloans';

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
        'employer_id',
        'channel_id',
        'funder_id',
        'funder_acc_number',
        'loan_type', // 1: Cash Loan; 2: Store Credit Loan; 3: Hybrid Loan; 4: Business Loan; 5: Recharge Loan
        'loan_status', // 0: Not Signed; 1: AWAIT FCB Approval; 2: Awaiting KYC Approval; 3: KYC Approved; 4: KYC Rejected; 5: Loan Authorized; 6: Loan Rejected; 7:Await Disbursement; 8:Disbursed; 9: Repaying; 10: Paid Back;
        'amount',
        'repay_freq',
        'tenure',
        'interestRate',
        'monthly',
        'disbursed',
        'appFee',
        'disbursefee',
        'charges',
        'product',
        'pprice',
        'install_date',
        'maturity_date',
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
        'employer_id'                         => 'string',
        'channel_id'                             => 'string',
        'funder_id'                             => 'string',
        'funder_acc_number'                             => 'string',
        'loan_type'                             => 'string',
        'loan_status'                             => 'string',
        'amount'                             => 'double',
        'repay_freq'                             => 'string',
        'tenure'                             => 'string',
        'interestRate'                             => 'integer',
        'monthly'                             => 'double',
        'disbursed'                             => 'double',
        'appFee'                             => 'double',
        'disbursefee'                             => 'double',
        'charges'                             => 'double',
        'product'                             => 'string',
        'pprice'                             => 'double',
        'install_date'                             => 'date',
        'maturity_date'                             => 'date',
        'isDisbursed'                             => 'boolean',
        'notes'                             => 'string',
        'locale'                             => 'string',
    ];
}
