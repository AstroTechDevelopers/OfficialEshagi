<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepresentativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('representatives', function (Blueprint $table) {
            $table->id();
            $table->string('creator');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('natid',30)->unique();
            $table->string('mobile',20)->unique();
            $table->string('partner_id',11);
            $table->string('branch')->nullable();
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
        Schema::dropIfExists('representatives');
    }
}
