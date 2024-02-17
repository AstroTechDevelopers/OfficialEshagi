<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsbDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ssb_details', function (Blueprint $table) {
            $table->id();
            $table->string('natid')->unique();
            $table->string('profession')->nullable();
            $table->string('sourcesOfIncome')->nullable();
            $table->double('currentNetSalary',12,2)->nullable();
            $table->double('grossSalary',12,2)->nullable();
            $table->string('hr_contact_name')->nullable();
            $table->string('hr_position')->nullable();
            $table->string('hr_email')->nullable();
            $table->string('hr_telephone')->nullable();
            $table->string('ecnumber')->nullable();
            $table->string('payrollAreaCode')->nullable();
            $table->date('dateJoined')->nullable();
            $table->string('accountType')->nullable();
            $table->string('yearsWithCurrentBank')->nullable();
            $table->string('otherBankAccountName')->nullable();
            $table->string('otherBankAccountNumber')->nullable();
            $table->string('otherBankName')->nullable();
            $table->string('bankStatement')->nullable();
            $table->string('highestQualification')->nullable();
            $table->string('maidenSurname')->nullable();
            $table->string('offerLetter')->nullable();
            $table->string('proofOfRes')->nullable();
            $table->boolean('proofOfResStatus')->default(false);
            $table->string('spouseEmployer')->nullable();
            $table->string('spouseName')->nullable();
            $table->string('spousePhoneNumber')->nullable();
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
        Schema::dropIfExists('ssb_details');
    }
}
