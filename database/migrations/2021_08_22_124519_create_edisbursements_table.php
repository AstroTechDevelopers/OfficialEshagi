<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edisbursements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eloan');
            $table->foreign('eloan')->references('id')->on('eloans')->onDelete('cascade')->onUpdate('cascade');;
            $table->string('disburser');
            $table->string('via');
            $table->double('amount',12,2);
            $table->string('reference')->unique();
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
        Schema::dropIfExists('edisbursements');
    }
}
