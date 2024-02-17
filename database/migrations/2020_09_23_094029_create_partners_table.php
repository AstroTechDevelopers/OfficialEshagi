<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('partner_name')->unique();
            $table->string('partner_type');
            $table->string('merchantname')->nullable();
            $table->string('business_type');
            $table->string('partnerDesc');
            $table->string('yearsTrading',3);
            $table->string('regNumber')->unique();
            $table->string('bpNumber')->unique();
            $table->string('propNumber',10);
            $table->string('street');
            $table->string('suburb');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('locale_id',5);
            $table->string('cfullname');
            $table->string('cdesignation');
            $table->string('telephoneNo');
            $table->string('cemail');
            $table->string('bank');
            $table->string('branch');
            $table->string('acc_number');
            $table->string('branch_code');
            $table->string('password');
            $table->boolean('partner_sign')->default(false);
            $table->string('signature')->nullable();
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
        Schema::dropIfExists('partners');
    }
}
