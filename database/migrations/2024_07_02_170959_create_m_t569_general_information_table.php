<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT569GeneralInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t569_general_information', function (Blueprint $table) {
            $table->id();
            $table->string('sender_reference')->nullable()->comment('sender Reference(20C)');
            $table->integer('sequence_number')->nullable()->comment('Sequence Number(28E)');
            $table->string('statement_number')->nullable()->comment('Statement number(13A)');
            $table->string('function_of_message')->nullable()->comment('Indicates part of a larger set(23G)');
            $table->timestamp('prep_date')->nullable()->comment('Preparation Date(98C)');
            $table->string('collateral_receive_provide_indicator')->nullable()->comment('Collateral Receive/Provide Indicator(22::H)');
            $table->string('statement_basis_indicator')->nullable()->comment('Statement basis(:22F::STBA//EOSP)');
            $table->string('statement_frequency_indicator')->nullable()->comment('Statement frequency(22F::SFRE//INDA)');
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
        Schema::dropIfExists('m_t569_general_information');
    }
}
