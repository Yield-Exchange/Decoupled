<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateOperatorToTradeCountersAndOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_c_g_offers', function (Blueprint $table) {
            //
            $table->renameColumn("rate_held_until","trade_date");
        });
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            //
            $table->renameColumn("rate_held_until","trade_date");
        });

        Schema::table('c_t_trade_request_offer_counter_offers', function (Blueprint $table) {
            //
            $table->dateTime("trade_date");
        });
        Schema::table('c_t_trade_request_offer_counter_offers_archive', function (Blueprint $table) {
            //
            $table->dateTime("trade_date");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_request_c_g_offers', function (Blueprint $table) {
            //
            $table->renameColumn('trade_date', 'rate_held_until');
        });
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            //
            $table->renameColumn('trade_date', 'rate_held_until');
        });

        Schema::table('c_t_trade_request_offer_counter_offers', function (Blueprint $table) {
            //
            $table->dropColumn("trade_date");
        });
        Schema::table('c_t_trade_request_offer_counter_offers_archive', function (Blueprint $table) {
            //
            $table->dropColumn("trade_date");
        });
    }
}
