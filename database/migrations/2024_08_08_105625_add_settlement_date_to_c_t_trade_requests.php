<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettlementDateToCTTradeRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_requests', function (Blueprint $table) {
            $table->unsignedBigInteger("trade_allowed_settlement_period_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_requests', function (Blueprint $table) {
            //
            $table->dropColumn("trade_allowed_settlement_period_id");
        });
    }
}
