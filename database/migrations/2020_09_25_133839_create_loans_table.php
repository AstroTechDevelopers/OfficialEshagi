<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
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
            $table->string('paybackPeriod');
            $table->integer('interestRate');
            $table->double('monthly',12,2);
            $table->double('disbursed',12,2);
            $table->double('appFee',12,2);
            $table->double('disbursefee',12,2)->default(0.00);
            $table->double('charges',12,2);
            $table->string('product')->nullable();
            $table->double('pprice', 12, 2)->nullable();
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
        Schema::dropIfExists('loans');
    }
}
