<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeCollateralBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_collateral_baskets', function (Blueprint $table) {
            $table->id();
            $table->string("basket_id");
            $table->string("basket_name");
            $table->enum("is_disabled",[0,1])->default(0);
            $table->dateTime("disabled_until")->nullable();            
            $table->timestamps();
        });

        Schema::create('trade_collateral_baskets_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("trade_collateral_baskets_id");
            $table->string("basket_id");
            $table->string("basket_name");
            $table->enum("is_disabled",[0,1])->default(0);
            $table->dateTime("disabled_until")->nullable();            
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
        Schema::dropIfExists('trade_collateral_baskets');
    }
}
