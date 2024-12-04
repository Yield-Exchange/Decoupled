<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCTTradeRequestCGOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_t_trade_request_c_g_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invitation_id');
            $table->string('offer_reference_no');
            $table->double("offer_minimum_amount");   
            $table->double("offer_maximum_amount");   
            $table->unsignedBigInteger('offer_trade_product_id');
            $table->enum("offer_term_length_type",['DAYS','MONTHS']);            
            $table->integer('offer_term_length');
            $table->integer('offer_interest_rate');
            $table->unsignedBigInteger("trade_collateral_basket_id");
            $table->unsignedBigInteger("trade_settlement_period_id");
            $table->dateTime("rate_held_until")->nullable();
            $table->enum('offer_status',['ACTIVE','OFFER_WITHDRAWN','EXPIRED','SELECTED','NOT_SELECTED','REQUEST_WITHDRAWN']);
            $table->timestamps();
        });
        Schema::create('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('c_r_trade_request_c_t_offers_id');
            $table->unsignedBigInteger('invitation_id');
            $table->string('offer_reference_no');
            $table->double("offer_minimum_amount");   
            $table->double("offer_maximum_amount");   
            $table->unsignedBigInteger('offer_trade_product_id');
            $table->enum("offer_term_length_type",['DAYS','MONTHS']);            
            $table->integer('offer_term_length');
            $table->integer('offer_interest_rate');
            $table->unsignedBigInteger("trade_collateral_basket_id");
            $table->unsignedBigInteger("trade_settlement_period_id");
            $table->dateTime("rate_held_until")->nullable();
            $table->enum('offer_status',['ACTIVE','OFFER_WITHDRAWN','EXPIRED','SELECTED','NOT_SELECTED','REQUEST_WITHDRAWN']);
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
        Schema::dropIfExists('c_r_trade_request_c_t_offers');
        Schema::dropIfExists('c_r_trade_request_c_t_offers_archive');        
    }
}
