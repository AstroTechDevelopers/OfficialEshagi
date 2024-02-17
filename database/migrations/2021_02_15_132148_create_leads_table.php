<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('natid');
            $table->string('mobile');
            $table->string('password')->nullable();
            $table->string('token')->nullable();
            $table->string('ecnumber');
            $table->string('address');
            $table->ipAddress('signup_ip_address')->nullable();
            $table->boolean('activated')->default(true);
            $table->string('locale', 4);
            $table->boolean('isContacted')->default(false);
            $table->boolean('isSMSed')->default(false);
            $table->boolean('isSale')->default(false);
            $table->string('agent')->nullable();
            $table->timestamp('assignedOn')->nullable();
            $table->timestamp('completedOn')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('leads');
    }
}
