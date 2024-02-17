<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdexchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prodexchanges', function (Blueprint $table) {
            $table->id();
            $table->double('usd_rate', 12,2);
            $table->double('rand_rate', 12,2)->nullable();
            $table->double('rtgs_rate', 12,2)->nullable();
            $table->string('changed_by');
            $table->string('user_id');
            $table->string('partner_id')->nullable();
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
        Schema::dropIfExists('prodexchanges');
    }
}
