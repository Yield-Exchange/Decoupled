<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT558GeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t558_generals', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence_number')->nullable()->comment('Sequence Number(28E)');
            $table->string('reference')->nullable()->comment('Reference(20cA)');
            $table->string('function_of_message')->nullable()->comment('Indicates part of a larger set(23G)');
            $table->timestamp('execution_request_date')->nullable()->comment('Execution Requested  Date(98a EXRQ)');
            $table->timestamp('settlement_date')->nullable()->comment('Settlement Date(98A SETT)');
            $table->timestamp('prep_date')->nullable()->comment('Preparation Date(98A PREP)');
            $table->timestamp('trade_date')->nullable()->comment('Trade Date(98A TRAD)');
            $table->string('collateral_receive_provide_indicator')->nullable()->comment('Collateral Receive/Provide Indicator(22::H)');
            $table->string('eligibility')->nullable()->comment('Eligibility Set Profile Number (13B ELIG)');
            $table->string('exposure_type_indicator')->comment("Exposure Type Indicator (22H COLA)");
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
        Schema::dropIfExists('m_t558_generals');
    }
}
