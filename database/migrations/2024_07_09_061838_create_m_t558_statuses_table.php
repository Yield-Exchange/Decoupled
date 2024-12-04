<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT558StatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t558_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t558_general_id');
            $table->string("status")->nullable()->comment("Sttus (25D)");
            $table->string("collateral_approved_flag")->nullable()->comment("Collateral approved flag (17B CAPP)");
            $table->string("settlement_approved_flag")->nullable()->comment("Settlement approved flag (17B SAPP)");
            $table->string("collateral_instruction_narrative")->nullable()->comment("Collateral instruction narrative (70E CINS)");
            $table->string("reason_narrative")->nullable()->comment("Reason narrative (70D REAS)");
            $table->decimal("required_margin_amount",20,4)->nullable()->comment("Required margin amount (19A RMAG)");
            $table->decimal("collaterised_amount",20,4)->nullable()->comment("collaterised amount (19A ALAM)");
            $table->decimal("settled_amount",20,4)->nullable()->comment("Settled amount (19 ESTT)");
            $table->decimal("remaining_collaterised_amount",20,4)->nullable()->comment("Remaining collaterised amount (19A RALA)");
            $table->decimal("remaining_settlement_amount",20,4)->nullable()->comment("Remaining settlement amount (19A RSTT)");
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
        Schema::dropIfExists('m_t558_statuses');
    }
}
