<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZambiaLoan extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zambia_loans';

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
        'zambian_id',
        'partner_id',
        'channel_id',
        'ld_loan_id',
        'lo_approved',
        'lo_approver',
        'manager_approved',
        'manager_approver',
        'isDisbursed',
        'loan_status',
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
        'loan_decimal_places',
        'loan_interest_start_date',
        'loan_fees_pro_rata',
        'loan_do_not_adjust_remaining_pro_rata',
        'loan_first_repayment_pro_rata',
        'loan_first_repayment_date',
        'first_repayment_amount',
        'last_repayment_amount',
        'loan_override_maturity_date',
        'override_each_repayment_amount',
        'loan_interest_each_repayment_pro_rata',
        'loan_interest_schedule',
        'loan_principal_schedule',
        'loan_balloon_repayment_amount',
        'automatic_payments',
        'payment_posting_period',
        'total_amount_due',
        'balance_amount',
        'due_date',
        'total_paid',
        'child_status_id',
        'loan_override_sys_gen_penalty',
        'loan_manual_penalty_amount',
        'loan_status_id',
        'loan_title',
        'loan_description',
        'cf_11133_approval_date',
        'cf_11353_installment',
        'cf_11132_qty',
        'cf_11130_sales_rep',
        'cf_11136_account_num',
        'cf_11134_bank',
        'cf_11135_branch',
        'imei',
        'serial_num',
        'next_payment',
        'device',
        'device_model',
        'enrollment_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'user_id'                              => 'string',
        'zambian_id'                        => 'string',
        'partner_id'                         => 'string',
        'channel_id'                             => 'string',
        'ld_loan_id'                             => 'string',
        'lo_approved'                             => 'boolean',
        'lo_approver'                             => 'string',
        'manager_approved'                             => 'boolean',
        'manager_approver'                             => 'string',
        'isDisbursed'                             => 'boolean',
        'loan_status'                             => 'string',
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
        'loan_interest_start_date'                             => 'date',
        'loan_fees_pro_rata'                             => 'string',
        'loan_do_not_adjust_remaining_pro_rata'                             => 'string',
        'loan_first_repayment_pro_rata'                             => 'string',
        'loan_first_repayment_date'                             => 'date',
        'first_repayment_amount'                             => 'decimal:2',
        'last_repayment_amount'                             => 'decimal:2',
        'loan_override_maturity_date'                             => 'date',
        'override_each_repayment_amount'                             => 'string',
        'loan_interest_each_repayment_pro_rata'                             => 'string',
        'loan_interest_schedule'                             => 'string',
        'loan_principal_schedule'                             => 'string',
        'loan_balloon_repayment_amount'                             => 'decimal:2',
        'automatic_payments'                             => 'boolean',
        'payment_posting_period'                             => 'string',
        'total_amount_due'                             => 'decimal:2',
        'balance_amount'                             => 'decimal:2',
        'due_date'                             => 'date',
        'total_paid'                             => 'decimal:2',
        'child_status_id'                             => 'string',
        'loan_override_sys_gen_penalty'                             => 'string',
        'loan_manual_penalty_amount'                             => 'decimal:2',
        'loan_status_id'                             => 'string',
        'loan_title'                             => 'string',
        'loan_description'                             => 'string',
        'cf_11133_approval_date'                             => 'date',
        'cf_11353_installment'                             => 'decimal:2',
        'cf_11132_qty'                             => 'string',
        'cf_11130_sales_rep'                             => 'string',
        'cf_11136_account_num'                             => 'integer',
        'cf_11134_bank'                             => 'string',
        'cf_11135_branch'                             => 'string',
        'imei'                             => 'string',
        'serial_num'                             => 'string',
        'next_payment'                             => 'date',
        'device'                             => 'string',
        'device_model'                             => 'string',
        'enrollment_date'                             => 'date',
    ];
}
