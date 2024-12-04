<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT569FinancialSummaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t569_financial_summary_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t569_general_information_id');
            $table->string('eligibility', 20, 2)->nullable()->comment('Eligibility Set Profile Number(13B ELIG)');
            $table->string('party_b')->nullable()->comment("Party B (95R PTYB)");
            $table->string('triparty_agent')->nullable()->comment("triparty Agent (95R TRAG)");
            $table->decimal('total_collateral_required', 20, 2)->nullable()->comment('Total Collateral Required(19A::TCOR)');
            $table->decimal('collateral_value_held', 20, 2)->nullable()->comment('Value of Collateral Held (19A::COVA)');
            $table->decimal('margin_amount', 20, 2)->nullable()->comment('Margin Amount(19A::MARG)');
            $table->decimal('margin', 20, 2)->nullable()->comment('sum of collateral balance expressed as a percentage of sum of
collateral required of all exposures (92A::MARG)');
            $table->decimal('total_collateral_own', 20, 2)->nullable()->comment('sum of the collateral value of all exposures in the given service type (19A TVOC)');
            $table->decimal('total_collateral_reused', 20, 2)->nullable()->comment('sum of the collateral value of all exposures in the given service type(reused assets 19A TVRC)');
            $table->decimal('total_exposure_amount', 20, 2)->nullable()->comment('sum of the exposure amount of all the exposures in the given service type (19A TEXA)');
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
        Schema::dropIfExists('m_t569_financial_summary_details');
    }
}
