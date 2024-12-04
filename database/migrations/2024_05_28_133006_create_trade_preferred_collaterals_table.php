<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradePreferredCollateralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_preferred_collaterals', function (Blueprint $table) {
            $table->id();
            $table->string("collateral_name");
            $table->text("description")->nullable();
            $table->enum("status",['ACTIVE','INACTIVE'])->default('ACTIVE');
            $table->timestamps();
        });
        Schema::create('trade_preferred_collaterals_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("preferred_collaterals_id");
            $table->string("collateral_name");
            $table->text("description")->nullable();
            $table->enum("status",['ACTIVE','INACTIVE'])->default('ACTIVE');
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
        Schema::dropIfExists('trade_preferred_collaterals');
        Schema::dropIfExists('trade_preferred_collaterals_archive');
    }
}
