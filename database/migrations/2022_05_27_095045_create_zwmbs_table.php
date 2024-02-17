<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZwmbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zwmbs', function (Blueprint $table) {
            $table->id();
            $table->string('reviewer');
            $table->string('checked_by')->nullable();
            $table->string('fcb_stat')->nullable();
            $table->string('authorized')->nullable();
            $table->string('customer_number')->nullable()->unique();
            $table->string('account_number')->nullable()->unique();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('natid')->unique();
            $table->string('agent');
            $table->string('account_type');
            $table->string('maiden_name')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('driver_licence')->nullable();
            $table->string('race');
            $table->string('occupation');
            $table->string('employer_name');
            $table->string('employer_contact_person');
            $table->string('designation');
            $table->string('nature_employer');
            $table->string('kin_relationship');
            $table->string('kin_address');
            $table->string('mobile_banking_num');
            $table->boolean('mobile_banking')->default(false);
            $table->boolean('internet_banking')->default(false);
            $table->boolean('sms_alerts')->default(false);
            $table->boolean('bank_card_local')->default(false);
            $table->string('proof_of_res');
            $table->boolean('proof_of_res_stat');
            $table->string('proof_of_income');
            $table->boolean('proof_of_income_stat');
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
        Schema::dropIfExists('zwmbs');
    }
}
