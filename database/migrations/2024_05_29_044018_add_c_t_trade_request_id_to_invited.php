<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCTTradeRequestIdToInvited extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_invited_c_g_s', function (Blueprint $table) {
           
            $table->unsignedBigInteger("c_t_trade_request_id");
            
        });
        Schema::table('c_t_trade_request_invited_c_g_s_archive', function (Blueprint $table) {
           
            $table->unsignedBigInteger("c_t_trade_request_id");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_request_invited_c_g_s', function (Blueprint $table) {
           
            $table->dropColumn("c_t_trade_request_id");
        });
        Schema::table('c_t_trade_request_invited_c_g_s_archive', function (Blueprint $table) {
           
            $table->unsignedBigInteger("c_t_trade_request_id");
            
        });
    }
}
