<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funders', function (Blueprint $table) {
            $table->id();
            $table->string('locale_id', 5);
            $table->string('funder')->unique();
            $table->string('funder_acc_num');
            $table->string('contact_fname');
            $table->string('contact_lname');
            $table->string('email')->unique();
            $table->string('tel_no')->unique();
            $table->string('support_email');
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
        Schema::dropIfExists('funders');
    }
}
