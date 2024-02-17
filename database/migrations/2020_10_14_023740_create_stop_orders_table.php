<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stop_orders', function (Blueprint $table) {
            $table->id();
            $table->string('client',30);
            $table->string('loanid',30);
            $table->string('bank', 30);
            $table->string('bank_officer', 50);
            $table->string('loan_officer', 50);
            $table->string('status',4);
            $table->text('comments');
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
        Schema::dropIfExists('stop_orders');
    }
}
