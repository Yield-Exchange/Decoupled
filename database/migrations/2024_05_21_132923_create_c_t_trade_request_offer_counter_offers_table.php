<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCTTradeRequestOfferCounterOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_t_trade_request_offer_counter_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->string('offer_reference_no');
            $table->double("offer_minimum_amount");   
            $table->double("offer_maximum_amount");   
            // $table->unsignedBigInteger('offer_trade_product_id')->nullable();
            $table->unsignedBigInteger("requested_by_user_id");
            $table->unsignedBigInteger("requested_by_organization_id");
            $table->enum("rate_type",['FIXED','VARIABLE'])->default('FIXED');
            $table->enum("rate_operator",['+','-'])->nullable();
            $table->unsignedBigInteger("variable_rate_type_id")->nullable();
            $table->integer("variable_rate_type_value")->nullable();    
            $table->integer("fixed_rate")->nullable();                     
            $table->enum('status',['ACCEPTED','DECLINED','COUNTERED','PENDING','EXPIRED','EDITED','CLOSED_ON_COUNTERED','CLOSED_ON_OFFER_SELECTION'])->default('PENDING');             
            $table->dateTime("offer_expiry")->nullable();
            $table->dateTime("counter_offer_expiry")->nullable();      
            $table->string("special_instructions")->nullable(); 
            $table->string("product_disclosure_statement")->nullable(); 
            $table->string("product_disclosure_url")->nullable(); 
            $table->timestamps();
            
        });
        Schema::create('c_t_trade_request_offer_counter_offers_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('c_r_trade_request_offer_counter_offers_id');
            $table->unsignedBigInteger('offer_id');
            $table->string('offer_reference_no');
            $table->double("offer_minimum_amount");   
            $table->double("offer_maximum_amount");   
            // $table->unsignedBigInteger('offer_trade_product_id')->nullable();
            $table->unsignedBigInteger("requested_by_user_id");
            $table->unsignedBigInteger("requested_by_organization_id");
            $table->enum("rate_type",['FIXED','VARIABLE'])->default('FIXED');
            $table->enum("rate_operator",['+','-'])->nullable();
            $table->unsignedBigInteger("variable_rate_type_id")->nullable();
            $table->integer("variable_rate_type_value")->nullable();    
            $table->integer("fixed_rate")->nullable();                     
            $table->enum('status',['ACCEPTED','DECLINED','COUNTERED','PENDING','EXPIRED','EDITED','CLOSED_ON_COUNTERED','CLOSED_ON_OFFER_SELECTION'])->default('PENDING');            
            $table->dateTime("offer_expiry")->nullable();
            $table->dateTime("counter_offer_expiry")->nullable();      
            $table->string("special_instructions")->nullable(); 
            $table->string("product_disclosure_statement")->nullable(); 
            $table->string("product_disclosure_url")->nullable(); 
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
        Schema::dropIfExists('c_t_trade_request_offer_counter_offers');
        Schema::dropIfExists('c_t_trade_request_offer_counter_offers_archive');
        
    }
}
