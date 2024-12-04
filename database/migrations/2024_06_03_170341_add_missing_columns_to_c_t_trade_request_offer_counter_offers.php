<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToCTTradeRequestOfferCounterOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_offer_counter_offers', function (Blueprint $table) {
            $table->double('variable_rate_value', 15, 2)->default(0.00);
        });
        Schema::table('c_t_trade_request_offer_counter_offers_archive', function (Blueprint $table) {
            $table->double('variable_rate_value', 15, 2)->default(0.00);
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
            $table->dropColumn("variable_rate_value");
        });
        Schema::table('c_t_trade_request_offer_counter_offers_archive', function (Blueprint $table) {        
            $table->dropColumn("variable_rate_value");
        });
    }
}
