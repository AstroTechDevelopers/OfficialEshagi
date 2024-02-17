<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZambiaPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zambia_payments', function (Blueprint $table) {
            $table->id();
            $table->string('creator');
            $table->unsignedBigInteger('locale')->nullable();
            $table->unsignedBigInteger('ld_repayment_id')->nullable();
            $table->unsignedBigInteger('ld_loan_id')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->decimal('amount',12,2);
            $table->string('payment_method');
            $table->string('collector_id');
            $table->date('collection_date');
            $table->string('rem_schedule', 25);
            $table->string('description')->nullable();
            $table->string('reference_num')->unique();
            $table->boolean('isValid')->default(true);
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
        Schema::dropIfExists('zambia_payments');
    }
}
