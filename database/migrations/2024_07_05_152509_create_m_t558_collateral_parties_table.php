<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT558CollateralPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t558_collateral_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t558_general_id');
            $table->string('party')->nullable()->comment('Party Identifier(95A)');
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
        Schema::dropIfExists('m_t558_collateral_parties');
    }
}
