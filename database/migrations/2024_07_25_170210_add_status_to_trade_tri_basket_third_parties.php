<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToTradeTriBasketThirdParties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_tri_basket_third_parties', function (Blueprint $table) {
            
            $table->enum("basket_status",['PENDING', 'ACTIVE', 'ARCHIVED','VERIFIED','ATTENTION'])->default("ACTIVE");
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
        Schema::table('trade_tri_basket_third_parties', function (Blueprint $table) {
            
            $table->dropColumn("basket_status");
        });
    }
}
