<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTradeCollateralBasketsDropBasketId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_collateral_baskets', function (Blueprint $table) {
          $table->dropColumn("basket_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trade_collateral_baskets', function (Blueprint $table) {
            //
            $table->string("basket_id");
        });
    }
}
