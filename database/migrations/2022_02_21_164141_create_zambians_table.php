<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZambiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zambians', function (Blueprint $table) {
            $table->id();
            $table->string('creator');
            $table->string('ld_borrower_id', 20)->nullable();
            $table->boolean('officer_stat')->default(false);
            $table->string('officer')->nullable();
            $table->boolean('manager_stat')->default(false);
            $table->string('manager')->nullable();
            $table->string('title',5);
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('nrc')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->date('dob');
            $table->string('gender', 6);
            $table->string('business_employer')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('landline')->nullable();
            $table->string('work_status')->nullable();
            $table->string('credit_score')->nullable();
            $table->string('pass_photo')->nullable();
            $table->string('nrc_pic')->nullable();
            $table->string('por_pic')->nullable();
            $table->string('pslip_pic')->nullable();
            $table->string('sign_pic')->nullable();
            $table->string('description')->nullable();
            $table->string('files')->nullable();
            $table->string('loan_officer_access')->nullable();
            $table->string('institution')->nullable();
            $table->string('ec_number')->nullable();
            $table->string('department')->nullable();
            $table->string('kin_name');
            $table->string('kin_relationship');
            $table->string('kin_address');
            $table->string('kin_number');
            $table->string('bank_name');
            $table->string('bank_account');
            $table->string('branch');
            $table->double('cred_limit',12,2)->default(0.00);
            $table->string('otp')->nullable();
            $table->uuid('savings_acc')->nullable();
            $table->string('savings_id', 50)->nullable();
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
        Schema::dropIfExists('zambians');
    }
}
