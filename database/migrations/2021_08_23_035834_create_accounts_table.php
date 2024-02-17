<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ledger');
            $table->foreign('ledger')->references('id')->on('ledgers')->onDelete('cascade')->onUpdate('cascade');;
            $table->string('natid');
            $table->string('reference');
            $table->double('debit',13,2)->default(0.00);
            $table->double('credit',13,2)->default(0.00);
            $table->string('status',4);
            $table->text('description');
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
        Schema::dropIfExists('accounts');
    }
}
