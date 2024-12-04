<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTradeTriBasketThirdParties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('trade_tri_basket_third_parties_archive', function (Blueprint $table) {
            $table->id();
            $table->string("basket_id");
            $table->unsignedBigInteger("trade_tri_basket_third_parties_id");
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("trade_collateral_basket_id");
            $table->enum("is_active",[0,1])->default(1);   
            $table->unsignedBigInteger("performed_by")->nullable();
            $table->string("prompting_action")->nullable();         
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
        
        Schema::dropIfExists('trade_tri_basket_third_parties_archive');
    }
}
