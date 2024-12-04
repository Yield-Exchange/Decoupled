<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCGTradeRequestInvitedCTOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_g_trade_request_invited_c_t_offers', function (Blueprint $table) {         
            $table->id();
            $table->unsignedBigInteger('c_g_trade_request_invited_c_t_id');
            $table->string('offer_reference_no');
            $table->double("offer_minimum_amount");   
            $table->double("offer_maximum_amount");   
            $table->unsignedBigInteger('offer_trade_product_id');
            $table->enum("offer_term_length_type",['DAYS','MONTHS']);            
            $table->integer('offer_term_length');
            $table->integer('offer_interest_rate');
            $table->unsignedBigInteger("trade_collateral_basket_id");
            $table->dateTime("rate_valid_until")->nullable();
            $table->unsignedBigInteger("interest_calculation_options_id");
            $table->enum('offer_status',['ACTIVE','OFFER_WITHDRAWN','EXPIRED','SELECTED','NOT_SELECTED','REQUEST_WITHDRAWN'])->default("ACTIVE");
            $table->timestamps();
        });
        Schema::create('c_g_trade_request_invited_c_t_offers_archive', function (Blueprint $table) {         
            $table->id();            
            $table->unsignedBigInteger('c_g_trade_request_invited_c_t_offer_id');
            $table->unsignedBigInteger('c_g_trade_request_invited_c_t_id');
            $table->string('offer_reference_no');
            $table->double("offer_minimum_amount");   
            $table->double("offer_maximum_amount");   
            $table->unsignedBigInteger('offer_trade_product_id');
            $table->enum("offer_term_length_type",['DAYS','MONTHS']);            
            $table->integer('offer_term_length');
            $table->integer('offer_interest_rate');
            $table->unsignedBigInteger("trade_collateral_basket_id");
            $table->dateTime("rate_valid_until")->nullable();
            $table->unsignedBigInteger("interest_calculation_options_id");
            $table->enum('offer_status',['ACTIVE','OFFER_WITHDRAWN','EXPIRED','SELECTED','NOT_SELECTED','REQUEST_WITHDRAWN'])->default("ACTIVE");
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
        Schema::dropIfExists('c_g_trae_request_invited_c_t_offers');
    }
}
