<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCounterOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->double('maximum_amount');
            $table->double('minimum_amount');
            $table->float('offered_interest_rate');
            $table->dateTime('offer_expiry');
            $table->dateTime('counter_offer_expiry')->nullable();
            $table->string('product_disclosure_statement');
            $table->string('product_disclosure_url');
            $table->string('special_instructions');
            $table->unsignedBigInteger('requested_by_user_id');
            $table->unsignedBigInteger('requested_by_organization_id');
            $table->enum('status',['ACCEPTED','DECLINED','COUNTERED','PENDING','EXPIRED'])->default('PENDING');
            $table->enum('rate_type',['VARIABLE','FIXED'])->default('FIXED');
            $table->enum('rate_operator',['+','-'])->nullable();
            $table->float('prime_rate')->nullable();
            $table->float('fixed_rate')->nullable();
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
        Schema::dropIfExists('counter_offers');
    }
}
