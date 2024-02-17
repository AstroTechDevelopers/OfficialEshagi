<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('natid')->unique();
            $table->string('mobile')->unique();
            $table->string('bot_number')->nullable();
            $table->string('bot_otp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('password_changed')->default(false);
            $table->timestamp('pwd_last_changed')->nullable();
            $table->rememberToken();
            $table->boolean('activated')->default(false);
            $table->string('token');
            $table->string('utype',20);
            $table->string('locale',4);
            $table->text('google2fa_secret')->nullable();
            $table->ipAddress('signup_ip_address')->nullable();
            $table->ipAddress('signup_confirmation_ip_address')->nullable();
            $table->ipAddress('signup_sm_ip_address')->nullable();
            $table->ipAddress('admin_ip_address')->nullable();
            $table->ipAddress('updated_ip_address')->nullable();
            $table->ipAddress('deleted_ip_address')->nullable();
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
        Schema::dropIfExists('users');
    }
}
