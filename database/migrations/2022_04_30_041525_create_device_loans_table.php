<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');;
            $table->string('client_id',11);
            $table->string('partner_id',11)->nullable();
            $table->string('channel_id');
            $table->string('funder_id',11);
            $table->string('funder_acc_number');
            $table->string('loan_number')->unique()->nullable();
            $table->string('loan_type',4);
            $table->string('loan_status',4);
            $table->boolean('isPayrollBased');
            $table->double('deposit_prct',12,2);
            $table->double('deposit',12,2);
            $table->double('amount',12,2);
            $table->double('balance',12,2);
            $table->string('paybackPeriod');
            $table->integer('interestRate');
            $table->double('monthly',12,2);
            $table->double('disbursed',12,2);
            $table->double('appFee',12,2);
            $table->double('disbursefee',12,2)->default(0.00);
            $table->double('charges',12,2);
            $table->string('serial_num')->nullable();
            $table->string('device');
            $table->string('device_model');
            $table->date('enrollment_date')->nullable();
            $table->string('imei')->nullable();
            $table->date('next_payment')->nullable();
            $table->boolean('isDisbursed')->default(false);
            $table->text('notes')->nullable();
            $table->string('locale', 4);
            $table->string('loan_product_id',10)->nullable();
            $table->unsignedBigInteger('ld_borrower_id')->nullable();
            $table->string('loan_application_id',10)->nullable();
            $table->string('loan_disbursed_by_id',10)->nullable();
            $table->decimal('loan_principal_amount',12,2)->nullable();
            $table->date('loan_released_date')->nullable();
            $table->string('loan_interest_method')->nullable();
            $table->string('loan_interest_type')->nullable();
            $table->string('loan_interest_period')->nullable();
            $table->decimal('loan_interest')->default(0.00);
            $table->string('loan_duration_period')->nullable();
            $table->integer('loan_duration')->nullable();
            $table->integer('loan_payment_scheme_id')->nullable();
            $table->integer('loan_num_of_repayments')->nullable();
            $table->string('loan_decimal_places')->nullable();
            $table->decimal('total_amount_due',12, 2)->nullable();
            $table->decimal('balance_amount',12, 2)->nullable();
            $table->string('loan_status_id',10)->nullable();
            $table->string('loan_description')->nullable();
            $table->date('cf_11133_approval_date')->nullable();
            $table->decimal('cf_11353_installment')->nullable();
            $table->string('cf_11132_qty')->nullable();
            $table->string('cf_11130_sales_rep')->nullable();
            $table->integer('cf_11136_account_num')->nullable();
            $table->string('cf_11134_bank')->nullable();
            $table->string('cf_11135_branch')->nullable();
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
        Schema::dropIfExists('device_loans');
    }
}
