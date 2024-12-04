<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchNoColumnToCTTradeRequestOfferDeposits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            //
            $table->unsignedBigInteger("active_trade_events_batch_number");
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
            //
            $table->dropColumn("active_trade_events_batch_number");
        });
    }
}
