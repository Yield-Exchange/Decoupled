<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCTTradeRequestPreferredCollateralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_t_trade_request_preferred_collaterals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("c_t_trade_request_id");
            $table->unsignedBigInteger("preferred_collateral_id");
            $table->timestamps();
        });
        Schema::create('c_t_trade_request_preferred_collaterals_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("c_t_trade_request_preferred_collaterals_id");
            $table->unsignedBigInteger("c_t_trade_request_id");
            $table->unsignedBigInteger("preferred_collateral_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c_t_trade_request_preferred_collaterals');
        Schema::dropIfExists('c_t_trade_request_preferred_collaterals_archive');
    }
}
