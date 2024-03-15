<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDynamicFieldsOnFunders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('funders', function (Blueprint $table) {
            $table->integer('max_repayment_month');
            $table->integer('interest_rate_percentage');
            $table->boolean('require_deposit')->default(false);
            $table->integer('initial_deposit_percentage')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funders', function (Blueprint $table) {

        });
    }
}
