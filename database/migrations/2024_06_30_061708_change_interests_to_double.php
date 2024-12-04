<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInterestsToDouble extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_c_g_offers', function (Blueprint $table) {
            $table->float("offer_interest_rate",15,2)->change();
        });

        Schema::table('c_t_trade_request_offer_counter_offers', function (Blueprint $table) {
            $table->float("fixed_rate",15,2)->change();
            $table->float("variable_rate_type_value",15,2)->change();
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
            $table->integer("offer_interest_rate")->change();
        });

        Schema::table('c_t_trade_request_offer_counter_offers', function (Blueprint $table) {
            $table->integer("fixed_rate")->change();
            $table->integer("variable_rate_type_value")->change();
            
        });
    }
}
