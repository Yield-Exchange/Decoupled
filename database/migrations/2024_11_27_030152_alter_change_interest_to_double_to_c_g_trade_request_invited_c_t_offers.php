<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use  Illuminate\Support\Facades\DB;

class AlterChangeInterestToDoubleToCGTradeRequestInvitedCTOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_g_trade_request_invited_c_t_offers', function (Blueprint $table) {
            DB::statement('ALTER TABLE c_g_trade_request_invited_c_t_offers MODIFY offer_interest_rate DOUBLE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_g_trade_request_invited_c_t_offers', function (Blueprint $table) {
            //
            DB::statement('ALTER TABLE c_g_trade_request_invited_c_t_offers MODIFY offer_interest_rate INTEGER');
        });
    }
}
