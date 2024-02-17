<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->string('medium');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('natid');
            $table->string('mobile');
            $table->string('status');
            $table->longText('query');
            $table->string('agent')->nullable();
            $table->timestamp('opened_on')->nullable();
            $table->longText('action_taken')->nullable();
            $table->timestamp('resolved_on')->nullable();
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
        Schema::dropIfExists('queries');
    }
}
