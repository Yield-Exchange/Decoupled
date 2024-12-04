<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReasonToTradeEventsAndDeposits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_request_deposit_trade_events', function (Blueprint $table) {
            //
            $table->renameColumn("termination_reason","reason");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_request_deposit_trade_events', function (Blueprint $table) {
            $table->renameColumn("reason","termination_reason");
        });
    }
}
