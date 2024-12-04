<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarketplaceMissingColumnsToCTTradeRequestOfferCounterOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('c_t_trade_request_offer_counter_offers', function (Blueprint $table) {
           $table->unsignedBigInteger("c_g_trade_request_id")->nullable();
           $table->unsignedBigInteger("c_g_trade_request_invited_c_t_offer_id")->nullable();
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
            $table->dropColumn("c_g_trade_request_id");
            $table->dropColumn("c_g_trade_request_invited_c_t_offer_id");
        });
        
    }
}
