<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceLoan extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'device_loans';

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
        'loan_type', // 1: Device Loan; 2: Cash Loan;
        'loan_status', // 0: Not Signed; 1: New; 2: Credit Check; 3: KYC Loan Officer; 4: KYC Manager; 5: Loan Disk Initiation; 6: PayTrigger Enrollment; 7: Enrolled on PayTrigger; 8: Sync eShagi & PayTrigger; 9: Waiting Deposit Payment; 10: Device Released; 11: Declined; 12: Paid Back;
        'isPayrollBased',
        'deposit_prct',
        'deposit',
        'amount',
        'balance',
        'paybackPeriod',
        'interestRate',
        'monthly',
        'disbursed',
        'appFee',
        'disbursefee',
        'charges',
        'serial_num',
        'device',
        'device_model',
        'enrollment_date',
        'imei',
        'next_payment',
        'isDisbursed',
        'notes',
        'locale',
        'loan_product_id',
        'ld_borrower_id',
        'loan_application_id',
        'loan_disbursed_by_id',
        'loan_principal_amount',
        'loan_released_date',
        'loan_interest_method',
        'loan_interest_type',
        'loan_interest_period',
        'loan_interest',
        'loan_duration_period',
        'loan_duration',
        'loan_payment_scheme_id',
        'loan_num_of_repayments',
        'loan_status_id',
        'loan_decimal_places',
        'loan_description',
        'total_amount_due',
        'balance_amount',
        'cf_11133_approval_date',
        'cf_11353_installment',
        'cf_11132_qty',
        'cf_11130_sales_rep',
        'cf_11136_account_num',
        'cf_11134_bank',
        'cf_11135_branch',
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
        'paybackPeriod'                             => 'string',
        'interestRate'                             => 'integer',
        'monthly'                             => 'double',
        'disbursed'                             => 'double',
        'appFee'                             => 'double',
        'disbursefee'                             => 'double',
        'charges'                             => 'double',
        'product'                             => 'string',
        'serial_num'                             => 'string',
        'device'                             => 'string',
        'device_model'                             => 'string',
        'enrollment_date'                             => 'date',
        'imei'                             => 'string',
        'next_payment'                             => 'date',
        'isDisbursed'                             => 'boolean',
        'notes'                             => 'string',
        'locale'                             => 'string',
        'loan_product_id'                             => 'string',
        'ld_borrower_id'                             => 'string',
        'loan_application_id'                             => 'string',
        'loan_disbursed_by_id'                             => 'string',
        'loan_principal_amount'                             => 'decimal:2',
        'loan_released_date'                             => 'date',
        'loan_interest_method'                             => 'string',
        'loan_interest_type'                             => 'string',
        'loan_interest_period'                             => 'string',
        'loan_interest'                             => 'decimal:2',
        'loan_duration_period'                             => 'string',
        'loan_duration'                             => 'integer',
        'loan_payment_scheme_id'                             => 'integer',
        'loan_num_of_repayments'                             => 'integer',
        'loan_decimal_places'                             => 'integer',
        'total_amount_due'                             => 'decimal:2',
        'balance_amount'                             => 'decimal:2',
        'loan_status_id'                             => 'string',
        'loan_description'                             => 'string',
        'cf_11133_approval_date'                             => 'date',
        'cf_11353_installment'                             => 'decimal:2',
        'cf_11132_qty'                             => 'string',
        'cf_11130_sales_rep'                             => 'string',
        'cf_11136_account_num'                             => 'integer',
        'cf_11134_bank'                             => 'string',
        'cf_11135_branch'                             => 'string',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
