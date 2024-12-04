<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CorrectInvestmentTypeOnCTTradeRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_requests', function (Blueprint $table) {
            //
            $table->renameColumn('investiment_amount', 'investment_amount');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_requests', function (Blueprint $table) {
            //
            $table->renameColumn('investment_amount', 'investiment_amount');
        });
    }
}
