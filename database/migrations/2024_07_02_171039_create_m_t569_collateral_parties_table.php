<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT569CollateralPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t569_collateral_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t569_general_information_id');
            $table->string('party_id')->nullable()->comment('Party Identifier(95R)');
            $table->string('party_type')->nullable()->comment('Party Type(PTYA)');
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
        Schema::dropIfExists('m_t569_collateral_parties');
    }
}
