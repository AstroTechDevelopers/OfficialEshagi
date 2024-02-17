<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('creator');
            $table->string('title',5);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('natid')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->date('dob');
            $table->string('gender', 6);
            $table->string('marital_state',15);
            $table->integer('dependants');
            $table->string('nationality',100);
            $table->string('house_num');
            $table->string('street');
            $table->string('surburb');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('locale_id', 5);
            $table->string('employer_id',11)->nullable();
            $table->string('emp_sector',50);
            $table->string('employer');
            $table->string('ecnumber');
            $table->double('gross',12,2);
            $table->double('salary',12,2);
            $table->double('cred_limit',12,2);
            $table->double('usd_cred_limit',12,2)->default('0.00');
            $table->string('home_type',75);
            $table->string('otp')->nullable();
            $table->string('flag', 10)->nullable();
            $table->string('fsb_score')->nullable();
            $table->string('fsb_status')->nullable();
            $table->string('fsb_rating')->nullable();
            $table->string('reds_id')->nullable();
            $table->string('reds_type')->nullable();
            $table->string('reds_sub')->nullable();
            $table->string('reds_number')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
