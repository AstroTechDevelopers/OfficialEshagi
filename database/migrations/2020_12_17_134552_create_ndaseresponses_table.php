<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNdaseresponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ndaseresponses', function (Blueprint $table) {
            $table->id();
            $table->string('recid')->unique();
            $table->string('deductioncode');
            $table->string('reference')->unique();
            $table->string('idnumber');
            $table->string('ecnumber');
            $table->string('type');
            $table->string('status');
            $table->string('startdate');
            $table->string('enddate');
            $table->string('amount');
            $table->string('total')->nullable();
            $table->string('surname')->nullable();
            $table->string('bank')->nullable();
            $table->string('bankacc')->nullable();
            $table->string('message')->nullable();
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
        Schema::dropIfExists('ndaseresponses');
    }
}
