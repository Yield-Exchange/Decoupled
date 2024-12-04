<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateOperatorToCTTradeRequestOfferCounterOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_offer_counter_offers', function (Blueprint $table) {
            $table->string("rate_type")->default("fixed")->change();
            $table->double("offer_interest_rate", 15, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_request_offer_counter_offers', function (Blueprint $table) { 
                      
            $table->string("rate_type")->change(); 
            $table->dropColumn("offer_interest_rate");

        });
    }
}
