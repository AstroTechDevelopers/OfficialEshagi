<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');;
            $table->string('natid')->unique();
            $table->boolean('status')->default(false);
            $table->string('reviewer',30)->nullable();
            $table->string('cbz_status', 4)->default(0);
            $table->string('cbz_evaluator',30)->nullable();
            $table->string('kyc_notes')->nullable();
            $table->string('national_pic')->nullable();
            $table->boolean('national_stat')->default(false);
            $table->string('passport')->nullable();
            $table->string('passport_pic')->nullable();
            $table->boolean('passport_stat')->default(false);
            $table->string('dlicence')->nullable();
            $table->string('dlicence_pic')->nullable();
            $table->boolean('dlicence_stat')->default(false);
            $table->string('proofres')->nullable();
            $table->string('proofres_pic')->nullable();
            $table->boolean('proofres_stat')->default(false);
            $table->string('payslip_num')->nullable();
            $table->string('payslip_pic')->nullable();
            $table->boolean('payslip_stat')->default(false);
            $table->string('sign_id')->nullable();
            $table->string('sign_pic')->nullable();
            $table->boolean('sign_stat')->default(false);
            $table->string('kin_title',5);
            $table->string('kin_fname');
            $table->string('kin_lname');
            $table->string('kin_email')->nullable();
            $table->string('kin_work');
            $table->string('kin_number');
            $table->string('bank');
            $table->string('bank_acc_name');
            $table->string('branch');
            $table->string('branch_code');
            $table->string('acc_number');
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
        Schema::dropIfExists('kycs');
    }
}
