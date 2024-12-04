<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableChangeCurrencyToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_collateral_baskets', function (Blueprint $table) {
            $table->string("currency")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trade_collateral_baskets', function (Blueprint $table) {
            //
            $table->enum("currency",["CAD"])->default("CAD");
        });
    }
}
