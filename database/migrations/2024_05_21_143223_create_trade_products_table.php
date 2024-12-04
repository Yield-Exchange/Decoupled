<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('trade_products', function (Blueprint $table) {
            $table->id();
            $table->string("product_name");
            $table->enum("is_disabled",[0,1])->default(0);
            $table->dateTime("disabled_until")->nullable();            
            $table->timestamps();
        });

        Schema::create('trade_products_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("trade_products_id");
            $table->string("product_name");
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
        Schema::dropIfExists('trade_products');
        Schema::dropIfExists('trade_products_archive');
        
    }
}
