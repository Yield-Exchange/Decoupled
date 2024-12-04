<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeAllowedSettlementPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_allowed_settlement_periods', function (Blueprint $table) {
            $table->id();
            $table->string("trade_date_label")->default("T");
            $table->string("period_label")->default("Days");
            $table->integer("duration");
            $table->text("description")->nullable();
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
        Schema::dropIfExists('trade_allowed_settlement_periods');
    }
}
