<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOfferIdOnCTTradeRequestOfferDeposits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            $table->renameColumn("offer_id","c_t_trade_request_c_g_offer_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            $table->renameColumn("c_t_trade_request_c_g_offer_id","offer_id");
        });
    }
}
