<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masettings', function (Blueprint $table) {
            $table->id();
            $table->double('interest', 12 , 2);
            $table->double('self_interest', 12 , 2);
            $table->double('device_interest', 12 , 2);
            $table->double('creditRate', 12 , 2);
            $table->double('usd_creditRate', 12 , 2);
            $table->double('om_interest', 12 , 2);
            $table->double('usd_interest', 12 , 2);
            $table->string('weekly_target', 5);
            $table->string('loan_interest_method');
            $table->string('leads_allocation', 5);
            $table->string('fcb_username');
            $table->string('fcb_password');
            $table->string('reds_username');
            $table->string('reds_password');
            $table->string('ndas_username');
            $table->string('ndas_password');
            $table->string('crb_infinity_code');
            $table->string('crb_username');
            $table->string('crb_password');
            $table->string('signing_ceo');
            $table->text('ceo_encoded_signature');
            $table->string('cbz_authorizer', 350);
            $table->double('device_penalty', 12, 2);
            $table->double('loan_penalty', 12, 2);
            $table->double('zam_dev_upfront_fee', 12, 2);
            $table->string('bulksmsweb_baseurl');
            $table->string('last_changed_by');
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
        Schema::dropIfExists('masettings');
    }
}
