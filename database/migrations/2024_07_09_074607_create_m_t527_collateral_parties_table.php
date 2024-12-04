<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT527CollateralPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t527_collateral_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t527_general_id');
            $table->string("party_a")->nullable()->comment("Party A (95A PTYA)");
            $table->string("party_a_client")->nullable()->comment("Party A (95A CLPA)");
            $table->string("party_b")->nullable()->comment("Party B (95A PTYB)");
            $table->string("triparty_agent")->nullable()->comment("triparty agent (95P TRAG) ");
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
        Schema::dropIfExists('m_t527_collateral_parties');
    }
}
