<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateOperatorToCTTradeRequestCGOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_c_g_offers', function (Blueprint $table) {

          $table->enum("rate_operator",["+","-"])->default("+");
          
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

           $table->dropColumn("rate_operator");

        });
    }
}
