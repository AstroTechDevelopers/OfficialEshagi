<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantKycsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_kycs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade')->onUpdate('cascade');
            $table->string('natid')->unique();
            $table->boolean('loan_officer')->default(false);
            $table->string('approver')->nullable();
            $table->boolean('manager')->default(false);
            $table->string('manager_approver')->nullable();
            $table->string('cert_incorp')->nullable();
            $table->boolean('cert_incorp_stat')->default(false);
            $table->string('national_id1')->nullable();
            $table->boolean('national_id1_stat')->default(false);
            $table->string('national_id2')->nullable();
            $table->boolean('national_id2_stat')->default(false);
            $table->string('proof_of_res')->nullable();
            $table->boolean('proof_of_res_stat')->default(false);
            $table->string('cr6')->nullable();
            $table->boolean('cr6_stat')->default(false);
            $table->string('cr14')->nullable();
            $table->boolean('cr14_stat')->default(false);
            $table->string('bus_licence')->nullable();
            $table->boolean('bus_licence_stat')->default(false);
            $table->string('pphoto1')->nullable();
            $table->boolean('pphoto1_stat')->default(false);
            $table->string('pphoto2')->nullable();
            $table->boolean('pphoto2_stat')->default(false);
            $table->string('other')->nullable();
            $table->boolean('other_stat')->default(false);
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('merchant_kycs');
    }
}
