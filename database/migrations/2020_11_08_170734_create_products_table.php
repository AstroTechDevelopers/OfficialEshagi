<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('loandevice')->default(false);
            $table->string('creator');
            $table->string('pcode')->unique();
            $table->string('serial')->unique();
            $table->string('pname');
            $table->string('model');
            $table->string('descrip', 255);
            $table->double('price', 12, 2);
            $table->double('usd_price', 12, 2)->nullable();
            $table->foreignId('partner_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
}
