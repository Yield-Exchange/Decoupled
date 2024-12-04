<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTradeSettlementPeriodIdToCTTradeRequestOfferCounterOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_offer_counter_offers', function (Blueprint $table) {
            //
            $table->unsignedBigInteger("trade_settlement_period_id");
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
            //
            $table->dropColumn("trade_settlement_period_id");
        });
    }
}
