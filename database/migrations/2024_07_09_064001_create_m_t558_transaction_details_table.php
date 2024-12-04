<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT558TransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t558_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t558_general_id');
            $table->timestamp('closing_date_time')->nullable()->comment('Closing Date/Time (98A TERM)');
            $table->string("deal_transaction_details")->nullable()->comment("Deal transaction details (19A DEAL)");
            $table->string("method_of_interest_computation")->nullable()->comment("Method of interest computation (22F MICO)");
            $table->decimal("transaction_amount",20,4)->nullable()->comment("Transaction amount (19A TRAA)");
            $table->decimal("termination_transaction_amount",20,4)->nullable()->comment("Termination transaction amount (19A TRTE)");
            $table->decimal("pricing_rate",20,4)->nullable()->comment("Pricing rate (92A PRIC)");
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
        Schema::dropIfExists('m_t558_transaction_details');
    }
}
