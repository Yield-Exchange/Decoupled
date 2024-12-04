<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCTTradeRequestsArchiveAddConventionIdSettlementDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_requests_archive', function (Blueprint $table) {
            $table->dateTime("settlement_date")->nullable();
            $table->unsignedBigInteger("interest_calculation_options_id")->nullable();         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_requests_archive', function (Blueprint $table) {
            //
        });
    }
}
