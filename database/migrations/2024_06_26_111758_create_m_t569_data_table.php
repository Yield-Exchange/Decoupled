<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT569DataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t569_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('sequence_number')->nullable()->comment('Sequence number of the message (:28E:)');
            $table->string('status_code')->nullable()->comment('Status code (:13A::STAT)'); 
            $table->string('reference')->nullable()->comment('Unique reference for the message (:20C::SEME)');
            $table->string('function_of_message')->nullable()->comment('Function of the message (e.g., NEWM) (:23G:)');
            $table->decimal('preparation_date',16,2)->default(0.00)->comment('Preparation date (:98C::PREP)');
            $table->string('collateral_status')->nullable()->comment('Collateral status (:22F::STBA)');
            $table->string('collateral_type')->nullable()->comment('Type of collateral (:22F::COLA)');
            $table->string('collateral_reuse')->nullable()->comment('Collateral reuse indicator (:22H::REPR)');
            $table->string('collateral_free')->nullable()->comment('Collateral free indicator (:22F::SFRE)');  
            $table->decimal('collateral_value',16,2)->default(0.00)->comment('Collateral value (:19A::COVA)');  
            $table->string('party_a')->nullable()->comment('Party A Identifier. e.g, "CEDE" (Central Depository) and "SWIR" (SWIFT)(:95R::PTYA)');
            $table->decimal('total_exposure_amount',16,2)->default(0.00)->comment('Total exposure amount (:19A::TEXA)');
            $table->decimal('total_collateral_received',16,2)->default(0.00)->comment('Total collateral received (:19A::TCOR)');
            $table->decimal('total_collateral_value',16,2)->default(0.00)->comment('Total collateral value (:19A::COVA)');
            $table->decimal('margin_amount',16,2)->default(0.00)->comment('Margin amount (:19A::MARG)');
            $table->decimal('margin_amount2',16,2)->default(0.00)->comment('Margin amount (:19A::MARG)');
            $table->decimal('margin_percentage',16,2)->default(0.00)->comment('Margin percentage (:92A::MARG)');
            $table->dateTime('valuation_date')->nullable()->comment('Valuation date (:98C::VALN)');
            $table->dateTime('valuation_date2')->nullable()->comment('Valuation date (:98C::SETT)');
            $table->decimal('total_valuation',16,2)->default(0.00)->comment('Total valuation (:19A::TVOC)');
            $table->decimal('total_received',16,2)->default(0.00)->comment('Total received (:19A::TVRC)');
            $table->string('eligibility')->nullable()->comment('Eligibility indicator (:13B::ELIG)');
            $table->string('party_b')->nullable()->comment('Party B Identifier. e.g, "CEDE" (Central Depository) and "SWIR" (SWIFT)(:95R::PTYB)');
            $table->string('transaction_agent')->nullable()->comment('Transaction agent details (:95R::TRAG)');
            $table->string('trade_reference')->nullable()->comment('Trade reference number (:20C::CLTR)');
            $table->string('trade_counter_reference')->nullable()->comment('Trade counter reference number (:20C::TCTR)');
            $table->string('term')->nullable()->comment('Term details (:98B::TERM)');
            $table->dateTime('execution_request_date')->nullable()->comment('Execution request date (:98A::EXRQ)');
            $table->decimal('transaction_amount',16,2)->default(0.00)->comment('Transaction amount (:19A::TEXA)');
            $table->decimal('transaction_collateral',16,2)->default(0.00)->comment('Transaction collateral (:19A::TCOR)');
            $table->decimal('transaction_fees',16,2)->default(0.00)->comment('Transaction fees (:19A::TCFA)');
            $table->string('transaction_type')->nullable()->comment('Transaction type (:25D::TREX)');
            $table->decimal('price',16,2)->default(0.00)->comment('Price (:92A::PRIC)');
            $table->decimal('market_price',16,2)->default(0.00)->comment('Market price (:19A::MKTP)');
            $table->decimal('market_value',16,2)->default(0.00)->comment('Market value (:19A::MVPF)');
            $table->decimal('accrued_interest',16,2)->default(0.00)->comment('Accrued interest (:19A::ACRU)');
            $table->decimal('exchange_rate',16,2)->nullable()->comment('Exchange rate (:92B::EXCH)');
            $table->decimal('valuation_factor',16,2)->nullable()->comment('Valuation factor (:92A::VAFC)');
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
        Schema::dropIfExists('m_t569_data');
    }
}
