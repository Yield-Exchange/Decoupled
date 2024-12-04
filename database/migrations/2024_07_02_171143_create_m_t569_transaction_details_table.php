<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT569TransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t569_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t569_general_information_id');
            $table->string('collateral_transaction_ref')->nullable()->comment('Collateral Transaction Reference(20C::CLTR)');
            $table->integer('transaction_count')->nullable()->comment('Transaction Count(20C::TCTR)');
            $table->string('term_type')->nullable()->comment('Term Type(98B::TERM)');
            $table->date('settlement_date')->nullable()->comment('Execution Request Date(98A::EXRQ)');
            $table->decimal('total_exposure_amount', 20, 2)->nullable()->comment('Total Exposure Amount(19A::TEXA)');
            $table->decimal('total_collateral_required', 20, 2)->nullable()->comment('Total Collateral Required(19A::TCOR)');
            $table->decimal('collateral_value', 20, 2)->nullable()->comment('Collateral Value(19A::COVA)');
            $table->decimal('margin_amount', 20, 2)->nullable()->comment('Margin Amount(19A::MARG)');
            $table->decimal('total_cash_flow', 20, 2)->nullable()->comment('Total Cash Flow(19A::TCFA)');
            $table->decimal('price', 20, 2)->nullable()->comment('Price(92A::PRIC)');
            $table->decimal('valuation_margin', 20, 2)->nullable()->comment('Valuation Margin(92A::MARG)');
            $table->string('transaction_extension')->nullable()->comment('Transaction Extension(25D::TREX)');
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
        Schema::dropIfExists('m_t569_transaction_details');
    }
}
