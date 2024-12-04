<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCgTradeRequestColumnsToCtOfferArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('cg_trade_request_id')->nullable()->after('interest_calculation_options_id');
            $table->unsignedBigInteger('cg_trade_request_offer_id')->nullable()->after('cg_trade_request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            //
            $table->dropColumn('cg_trade_request_id');
            $table->dropColumn('cg_trade_request_offer_id');
        });
    }
}
