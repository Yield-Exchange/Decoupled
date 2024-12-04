<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT527TransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t527_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t527_general_id');
            $table->timestamp('closing_date')->nullable()->comment('Closing Date (98A TERM)');
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
        Schema::dropIfExists('m_t527_transaction_details');
    }
}
