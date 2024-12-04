<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeCollateralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_collaterals', function (Blueprint $table) {
            $table->id();
            $table->string("collateral_name");
            $table->text("collateral_description");
            $table->boolean("is_disable")->default(0);
            $table->dateTime("disabled_until")->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('trade_collaterals');
    }
}
