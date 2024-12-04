<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchNoColumnToCTRequestDepositTradeEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_request_deposit_trade_events', function (Blueprint $table) {
            $table->bigInteger("batch_no");
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
            $table->dropColumn("batch_no");
        });
    }
}
