<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequestWithdrawalReasonToCtTradeRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_requests', function (Blueprint $table) {
            //
            $table->string("request_withdrawal_reason")->nullable();
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
            $table->dropColumn("request_withdrawal_reason");
        });
    }
}
