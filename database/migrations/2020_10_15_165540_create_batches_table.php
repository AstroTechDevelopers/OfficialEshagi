<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batchid',50)->unique();
            $table->string('recordsCount',10);
            $table->string('totalAmount',50);
            $table->string('deductionCode', 30);
            $table->string('status', 30);
            $table->timestamp('creationDate');
            $table->text('records')->nullable();
            $table->boolean('committed')->default(false);
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
        Schema::dropIfExists('batches');
    }
}
