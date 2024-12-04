<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCTTradeRequestCGOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_c_g_offers', function (Blueprint $table) {
            $table->unsignedBigInteger("trade_organization_collateral_id")->nullable();
            $table->string("trade_collateral_basket_id")->nullable()->change();
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
            $table->dropColumn("trade_organization_collateral_id");
            $table->string("trade_collateral_basket_id")->change();
        });
    }
}
