<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kycchanges', function (Blueprint $table) {
            $table->id();
            $table->string('client_id');
            $table->string('natid');
            $table->string('mobile_no')->nullable();
            $table->string('payslip')->nullable();
            $table->double('gross',12,2)->nullable();
            $table->double('net', 12, 2)->nullable();
            $table->boolean('status')->default(false);
            $table->string('reviewer')->nullable();
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
        Schema::dropIfExists('kycchanges');
    }
}
