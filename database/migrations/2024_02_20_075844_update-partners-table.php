<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('partnerDesc')->nullable()->change();
            $table->string('propNumber',10)->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->string('suburb')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('province')->nullable()->change();
            $table->string('bank')->nullable()->change();
            $table->string('branch')->nullable()->change();
            $table->string('acc_number')->nullable()->change();
            $table->string('branch_code')->nullable()->change();
            $table->string('signature')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partners', function (Blueprint $table) {
            //
        });
    }
}
