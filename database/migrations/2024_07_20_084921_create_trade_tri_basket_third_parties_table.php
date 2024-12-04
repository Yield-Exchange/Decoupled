<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeTriBasketThirdPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_tri_basket_third_parties', function (Blueprint $table) {
            $table->id();
            $table->string("basket_id");
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("trade_collateral_basket_id");
            $table->enum("is_active",[0,1])->default(1);            
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
        Schema::dropIfExists('trade_tri_basket_third_parties');
    }
}
