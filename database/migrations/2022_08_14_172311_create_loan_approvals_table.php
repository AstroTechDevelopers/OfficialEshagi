<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('user_id');
            $table->string('locale',5);
            $table->string('otp');
            $table->boolean('verified')->default(false);
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
        Schema::dropIfExists('loan_approvals');
    }
}
