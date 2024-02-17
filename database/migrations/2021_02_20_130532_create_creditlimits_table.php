<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditlimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditlimits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
            $table->double('grossSalary',12,2);
            $table->double('netSalary',12,2);
            $table->double('creditlimit',12,2);
            $table->double('usdGrossSalary',12,2)->default(0.00);
            $table->double('usdNetSalary',12,2)->default(0.00);
            $table->double('usdCreditlimit',12,2)->default(0.00);
            $table->string('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditlimits');
    }
}
