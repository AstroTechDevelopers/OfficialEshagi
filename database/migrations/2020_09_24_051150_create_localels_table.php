<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localels', function (Blueprint $table) {
            $table->id();
            $table->string('country')->unique();
            $table->string('country_short')->unique();
            $table->string('currency_code',10);
            $table->string('currency_name');
            $table->string('symbol',5);
            $table->string('country_code');
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
        Schema::dropIfExists('localels');
    }
}
