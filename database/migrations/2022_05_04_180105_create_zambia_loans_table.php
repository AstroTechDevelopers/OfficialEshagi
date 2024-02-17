<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZambiaLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zambia_loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');;
            $table->unsignedBigInteger('zambian_id');
            $table->string('partner_id',11)->nullable();
            $table->string('channel_id');
            $table->string('ld_loan_id')->unique()->nullable();
            $table->string('loan_status',10);
            $table->boolean('lo_approved')->default(false);
            $table->string('lo_approver')->nullable();
            $table->boolean('manager_approved')->default(false);
            $table->string('manager_approver')->nullable();
            $table->boolean('isDisbursed')->default(false);
            $table->string('loan_product_id',10);
            $table->unsignedBigInteger('ld_borrower_id')->nullable();
            $table->string('loan_application_id',10);
            $table->string('loan_disbursed_by_id',10);
            $table->decimal('loan_principal_amount',12,2);
            $table->date('loan_released_date');
            $table->string('loan_interest_method');
            $table->string('loan_interest_type');
            $table->string('loan_interest_period');
            $table->decimal('loan_interest')->default(0.00);
            $table->string('loan_duration_period');
            $table->integer('loan_duration');
            $table->integer('loan_payment_scheme_id');
            $table->integer('loan_num_of_repayments');
            $table->string('loan_decimal_places')->nullable();
            $table->date('loan_interest_start_date')->nullable();
            $table->string('loan_fees_pro_rata',10)->nullable();
            $table->string('loan_do_not_adjust_remaining_pro_rata',10)->nullable();
            $table->string('loan_first_repayment_pro_rata',10)->nullable();
            $table->date('loan_first_repayment_date')->nullable();
            $table->decimal('first_repayment_amount',12,2)->nullable();
            $table->decimal('last_repayment_amount',12,2)->nullable();
            $table->date('loan_override_maturity_date')->nullable();
            $table->string('override_each_repayment_amount')->nullable();
            $table->string('loan_interest_each_repayment_pro_rata', 5)->nullable();
            $table->string('loan_interest_schedule')->nullable();
            $table->string('loan_principal_schedule')->nullable();
            $table->decimal('loan_balloon_repayment_amount')->nullable();
            $table->boolean('automatic_payments')->default(false)->nullable();
            $table->string('payment_posting_period', 10)->nullable();
            $table->decimal('total_amount_due',12, 2)->nullable();
            $table->decimal('balance_amount',12, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('total_paid',12, 2)->nullable();
            $table->string('child_status_id', 20)->nullable();
            $table->string('loan_override_sys_gen_penalty',10)->nullable();
            $table->decimal('loan_manual_penalty_amount')->nullable();
            $table->string('loan_status_id',10)->nullable();
            $table->string('loan_title')->nullable();
            $table->string('loan_description')->nullable();
            $table->date('cf_11133_approval_date');
            $table->decimal('cf_11353_installment');
            $table->string('cf_11132_qty');
            $table->string('cf_11130_sales_rep');
            $table->integer('cf_11136_account_num')->nullable();
            $table->string('cf_11134_bank')->nullable();
            $table->string('cf_11135_branch')->nullable();
            $table->string('imei')->nullable();
            $table->string('serial_num')->nullable();
            $table->date('next_payment')->nullable();
            $table->string('device')->nullable();
            $table->string('device_model')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->timestamp('disbursed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zambia_loans');
    }
}
