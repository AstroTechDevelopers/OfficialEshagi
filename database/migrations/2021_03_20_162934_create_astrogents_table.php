<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstrogentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('astrogents', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title', 5);
                $table->string('name')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('gender', 6);
                $table->string('email')->unique();
                $table->string('natid')->unique();
                $table->string('mobile')->unique();
                $table->string('otp')->nullable();
                $table->string('bank_acc_name')->nullable();
                $table->string('bank')->nullable();
                $table->string('branch')->nullable();
                $table->string('branch_code')->nullable();
                $table->string('accountNumber')->nullable();
                $table->string('address');
                $table->boolean('activated')->default(false);
                $table->string('reviewer',50)->nullable();
                $table->string('locale',4)->default('1');
                $table->boolean('natidUpload')->default(false);
                $table->string('natidPic')->nullable();
                $table->boolean('signUpload')->default(false);
                $table->string('signaturePic')->nullable();
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
        Schema::dropIfExists('astrogents');
    }
}
