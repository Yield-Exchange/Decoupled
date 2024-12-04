<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT527DataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t527_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('sequence_number')->nullable()->comment('Sequence number of the message (:28E:)');
            $table->string('sender_reference')->nullable()->comment('Unique reference for the message (:20C::SEME)');
            $table->string('client_reference')->nullable()->comment('Client reference number (:20C::CLCI)');
            $table->string('collateral_transaction')->nullable()->comment('Collateral transaction reference (:20C::SCTR)');
            $table->string('function_of_message')->nullable()->comment('Function of the message (e.g., NEWM) (:23G:)');
            $table->dateTime('request_date')->nullable()->comment('Request date (:98A::EXRQ)');
            $table->string('collateral_intention')->nullable()->comment('Collateral intention (:22H::CINT)');
            $table->string('collateral_type')->nullable()->comment('Collateral allocation (:22H::COLA)');
            $table->string('collateral_receive_provide_indicator')->nullable()->comment('Collateral Receive/Provide Indicator(22H::REPR');
            $table->string('eligibility')->nullable()->comment('Eligibility (:13B::ELIG)');
            $table->string('party_a')->nullable()->comment('Party A Identifier. e.g, "CEDE" (Central Depository) and "SWIR" (SWIFT)(:95R::PTYA)');
            $table->string('collateral_party')->nullable()->comment('Collateral party details (:95R::CLPA)');
            $table->string('party_b')->nullable()->comment('Party B Identifier. e.g, "CEDE" (Central Depository) and "SWIR" (SWIFT)(:95R::PTYB)');
            $table->string('transaction_agent')->nullable()->comment('Transaction agent details (:95R::TRAG)');
            $table->string('term')->nullable()->comment('Term details (:98B::TERM)');
            $table->decimal('trade_amount',16,2)->default(0.00)->comment('Trade amount (:19A::TRAA)');
            $table->unsignedBigInteger('c_t_request_deposit_trade_events')->nullable();
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
        Schema::dropIfExists('m_t527_data');
    }
}
