<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArrearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrears', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan');
            $table->foreign('loan')->references('id')->on('eloans')->onDelete('cascade')->onUpdate('cascade');;
            $table->date('maturity_date');
            $table->integer('days_after');
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
        Schema::dropIfExists('arrears');
    }
}
