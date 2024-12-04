<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDummyToTradeTriBasketThirdParties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('trade_tri_basket_third_parties', function (Blueprint $table) {
            $table->boolean("is_dummy")->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('trade_tri_basket_third_parties', function (Blueprint $table) {
            $table->dropColumn("is_dummy");
        });
        
    }
}
