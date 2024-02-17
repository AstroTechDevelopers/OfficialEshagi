<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArrearActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrear_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('arrear');
            $table->foreign('arrear')->references('id')->on('arrears')->onDelete('cascade')->onUpdate('cascade');;
            $table->unsignedBigInteger('loan');
            $table->foreign('loan')->references('id')->on('eloans')->onDelete('cascade')->onUpdate('cascade');;
            $table->string('action');
            $table->boolean('result');
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
        Schema::dropIfExists('arrear_actions');
    }
}
