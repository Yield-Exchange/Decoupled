<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT569ValuationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t569_valuation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t569_general_information_id');
            $table->boolean('collateral_flag')->nullable()->comment('Collateral Flag(17B::COLL)');
            $table->boolean('security_flag')->nullable()->comment('Security Flag(17B::SECU)');
            $table->timestamp('settlement_date')->nullable()->comment('Settlement Date(98C::SETT)');
            $table->decimal('market_price', 20, 2)->nullable()->comment('Market Price(19A::MKTP)');
            $table->decimal('accrued_interest', 20, 2)->nullable()->comment('Accrued Interest(19A::ACRU)');
            $table->decimal('market_value_per_face_value', 20, 2)->nullable()->comment('Market Value per Face Value(19A::MVPF)');
            $table->string('exchange_rate')->nullable()->comment('Exchange Rate(92B::EXCH)');
            $table->decimal('valuation_factor', 20, 2)->nullable()->comment('Valuation Factor(92A::VAFC)');
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
        Schema::dropIfExists('m_t569_valuation_details');
    }
}
