<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT569CollateralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t569_collaterals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t569_general_information_id');
            $table->decimal('total_exposure_amount', 20, 2)->nullable()->comment('Total Exposure Amount(19A::TEXA)');
            $table->decimal('total_collateral_required', 20, 2)->nullable()->comment('Total Collateral Required(19A::TCOR)');
            $table->decimal('collateral_value', 20, 2)->nullable()->comment('Collateral Value(19A::COVA)');
            $table->decimal('margin_amount', 20, 2)->nullable()->comment('Margin Amount(19A::MARG)');
            $table->decimal('total_valuation', 20, 2)->nullable()->comment('Total Valuation(19A::TVOC)');
            $table->decimal('valuation_margin', 20, 2)->nullable()->comment('Valuation Margin(92A::MARG)');
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
        Schema::dropIfExists('m_t569_collaterals');
    }
}
