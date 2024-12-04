<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToRadeCollateralBaskets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_collateral_baskets', function (Blueprint $table) {
            //
            $table->enum("basket_status",['PENDING','ACTIVE','ARCHIVED','VERIFIED','ATTENTION'])->default("PENDING"); 
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
            $table->dropColumn("basket_status");
        });
    }
}
