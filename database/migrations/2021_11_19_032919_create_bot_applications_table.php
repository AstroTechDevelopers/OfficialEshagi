<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('client_id');
            $table->string('natid');
            $table->string('national_pic')->nullable();
            $table->string('passport_pic')->nullable();
            $table->boolean('approved')->default(false);
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
        Schema::dropIfExists('bot_applications');
    }
}
