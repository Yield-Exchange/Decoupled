<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCTTradeRequestCGOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_c_g_offers', function (Blueprint $table) {
            $table->renameColumn("trade_organization_collateral_id","trade_organization_collateral_c_u_s_i_p_s_id");
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
            $table->renameColumn("trade_organization_collateral_c_u_s_i_p_s_id","trade_organization_collateral_id");
        });
    }
}
