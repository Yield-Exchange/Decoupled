<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaketTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_basket_types', function (Blueprint $table) {
            $table->id();
            $table->string("basket_name");
            $table->text("basket_description")->nullable();
            $table->boolean("disabled")->default(0);
            $table->timestamps();
        });
        Schema::create('trade_basket_types_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("baket_type_id");
            $table->string("basket_name");
            $table->text("basket_description")->nullable();
            $table->boolean("disabled")->default(0);
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
        Schema::dropIfExists('trade_basket_types');
        Schema::dropIfExists('trade_basket_types_archive');
    }
}
