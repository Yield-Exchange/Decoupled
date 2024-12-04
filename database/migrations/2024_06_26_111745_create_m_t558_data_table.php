<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT558DataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t558_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('sequence_number')->nullable()->comment('Sequence number of the message (:28E:)');
            $table->string('sender_reference')->nullable()->comment('Unique reference for the message (:20C::SEME)');
            $table->string('client_reference')->nullable()->comment('Client reference number (:20C::CLCI)');
            $table->string('trade_reference')->nullable()->comment('Trade reference number (:20C::CLTR)');
            $table->string('function_of_message')->nullable()->comment('Function of the message (e.g., NEWM) (:23G:)');
            $table->dateTime('request_date')->nullable()->comment('Request date (:98A::EXRQ)');
            $table->string('collateral_intention')->nullable()->comment('Collateral intention (:22H::CINT)');
            $table->string('collateral_type')->nullable()->comment('Collateral allocation (:22H::COLA)');
            $table->string('collateral_reuse')->nullable()->comment('Collateral reuse indicator (:22H::REPR)');
            $table->string('auto_collateralization')->nullable()->comment('Auto collateralization indicator (:22F::AUTA)');
            $table->string('party_a')->nullable()->comment('Party A Identifier. e.g, "CEDE" (Central Depository) and "SWIR" (SWIFT)(:95R::PTYA)');
            $table->string('eligible_counterparty')->nullable()->comment('-Eligibility of the counterparty (:13B::ELIG)');
            $table->string('party_b')->nullable()->comment('Party B Identifier. e.g, "CEDE" (Central Depository) and "SWIR" (SWIFT)(:95R::PTYB)');
            $table->string('transaction_agent')->nullable()->comment('Transaction agent details (:95R::TRAG)');
            $table->string('instruction_processing')->nullable()->comment('Instruction processing status (:25D::IPRC)');
            $table->string('settlement_status')->nullable()->comment('Settlement status (:25D::SETT)');
            $table->string('allocation_status')->nullable()->comment('Allocation status (:25D::ALOC)');
            $table->string('term')->nullable()->comment('Term details (:98B::TERM)');
            $table->string('related_reference')->nullable()->comment('Related reference (:20C::RELA)');
            $table->decimal('collateral_amount',16,2)->default(0.00)->comment('Collateral amount (:19A::ALAM)');
            $table->decimal('requested_amount',16,2)->default(0.00)->comment('Requested amount (:19A::RALA)');
            $table->decimal('estimated_amount',16,2)->default(0.00)->comment('Estimated amount (:19A::ESTT)');
            $table->decimal('received_amount',16,2)->default(0.00)->comment('Received amount (:19A::RSTT)');
            $table->decimal('trade_amount',16,2)->default(0.00)->comment('Trade amount (:19A::TRAA)');
            $table->decimal('price',16,2)->default(0.00)->comment('Price (:92A::PRIC)');

            $table->unsignedBigInteger('m_t527_data_id')->nullable();
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
        Schema::dropIfExists('m_t558_data');
    }
}
