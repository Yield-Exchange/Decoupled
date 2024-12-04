<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTradeCollateralBasketsToIncludeNeededColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_collateral_baskets', function (Blueprint $table) {
             $table->dropColumn("basket_name");
             $table->unsignedBigInteger("trade_basket_type_id");
             $table->enum("currency",['CAD'])->default("CAD");
             $table->enum("type",['tri','bi']);
             $table->unsignedBigInteger("organization_id");
             $table->unsignedBigInteger("user_id");
             $table->string("rating")->nullable();
             $table->softDeletes(); 
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
            $table->string("basket_name");
            $table->dropColumn("trade_basket_type_id");
            $table->dropColumn("currency");
            $table->dropColumn("rating");
            $table->dropColumn("type");
            $table->dropColumn("organization_id");
            $table->dropColumn("user_id");
        });
    }
}
