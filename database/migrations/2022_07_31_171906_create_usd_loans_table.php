<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsdLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usd_loans', function (Blueprint $table) {
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
            $table->double('amount',12,2);
            $table->double('gross_amount',12,2);
            $table->string('tenure');
            $table->integer('interestRate');
            $table->double('monthly',12,2);
            $table->double('ags_commission',12,2);
            $table->double('app_fee',12,2);
            $table->double('est_fee',12,2);
            $table->double('insurance',12,2);
            $table->double('charges',12,2);
            $table->boolean('dd_approval')->default(false);
            $table->string('dd_approval_ref')->nullable();
            $table->string('disbursement_ref')->nullable();
            $table->string('ndasendaBatch',50)->nullable();
            $table->string('ndasendaRef1',50)->nullable();
            $table->string('ndasendaRef2',50)->nullable();
            $table->string('ndasendaState',50)->nullable();
            $table->string('ndasendaMessage')->nullable();
            $table->string('reds_loanstate')->nullable();
            $table->boolean('isDisbursed')->default(false);
            $table->text('notes')->nullable();
            $table->string('locale', 4);
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
        Schema::dropIfExists('usd_loans');
    }
}
