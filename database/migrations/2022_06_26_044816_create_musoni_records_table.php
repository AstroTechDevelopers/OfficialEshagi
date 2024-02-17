<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusoniRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musoni_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('natid')->unique();
            $table->boolean('status')->default(false);
            $table->string('reviewer');
            $table->string('authorizer')->nullable();
            $table->string('business_type');
            $table->string('business_start');
            $table->string('bus_address');
            $table->string('bus_city');
            $table->string('bus_country');
            $table->string('job_title');
            $table->string('kin_address');
            $table->string('kin_city');
            $table->string('kin_relationship');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('musoni_records');
    }
}
