<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLabelForBasketIdOnCTTradeRequestCGOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_c_g_offers', function (Blueprint $table) {
            
            $table->renameColumn("trade_collateral_basket_id","trade_tri_basket_third_party_id");
        });
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {            
            $table->renameColumn("trade_collateral_basket_id","trade_tri_basket_third_party_id");
            $table->unsignedBigInteger("trade_organization_collateral_id")->nullable();
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
            $table->renameColumn("trade_tri_basket_third_party_id","trade_collateral_basket_id");
        });
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            $table->unsignedBigInteger("trade_organization_collateral_id")->nullable();
            $table->renameColumn("trade_tri_basket_third_party_id","trade_collateral_basket_id");
        });
    }
}
