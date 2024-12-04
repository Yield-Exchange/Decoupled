<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT527GeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t527_generals', function (Blueprint $table) {
            $table->id();
            $table->string('sender_reference')->nullable()->comment('sender Reference(20C SEME)');
            $table->integer('sequence_number')->nullable()->comment('Page Number/Continuation Indicator (28E)');
            $table->string("sender_collateral_reference")->nullable()->comment("Sender's Collateral Reference (20C SCTR)");
            $table->string("receiver_collateral_reference")->nullable()->comment("Receiver's Collateral Reference (20C RCTR)");
            $table->string("client_collateral_reference")->nullable()->comment("Client’s Collateral Reference (20C CLCI)");
            $table->string("receiver_liquidity_reference")->nullable()->comment("Receiver's Liquidity Reference (20C TRCI)");
            $table->string('function_of_message')->nullable()->comment('Indicates part of a larger set(23G)');
            $table->string("instruction_type_indicator")->nullable()->comment("Instruction Type Indicator (22H CINT)");
            $table->string("exposure_type_indicator")->nullable()->comment("Exposure Type Indicator (22H COLA)");
            $table->string("client_indicator")->nullable()->comment("Client Indicator (22H REPR)");
            $table->string("eligibility")->nullable()->comment("Eligibility Set Profile Number (13B ELIG)");
            $table->dateTime("execution_requested_date")->nullable()->comment("Execution Requested date (98A EXPQ)");
            $table->dateTime("settlement_date")->nullable()->comment("Settlement date (98A SETT)");
            $table->dateTime("preparation_date")->nullable()->comment("Message Preparation Date/Time (98A PREP)");
            $table->dateTime("trade_date")->nullable()->comment("Trade Date (98A TRAD)");
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
        Schema::dropIfExists('m_t527_generals');
    }
}
