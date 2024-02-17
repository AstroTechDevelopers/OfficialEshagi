<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->string('paymt_number');
            $table->string('loanid', 30);
            $table->string('client_id', 30);
            $table->string('reds_number', 50);
            $table->double('payment', 12,2);
            $table->double('principal', 12,2);
            $table->double('interest', 12,2);
            $table->double('balance', 12,2);
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
        Schema::dropIfExists('repayments');
    }
}
