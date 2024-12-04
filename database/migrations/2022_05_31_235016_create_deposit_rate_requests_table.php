<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositRateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_rate_requests', function (Blueprint $table) {
            $table->id();
            $table->dateTime('gic_start_date');
            $table->dateTime('maturity_date')->nullable();
            $table->string('gic_number');
            $table->integer('deposit_id');
            $table->integer('offer_id');
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
        Schema::dropIfExists('deposit_rate_requests');
    }
}
