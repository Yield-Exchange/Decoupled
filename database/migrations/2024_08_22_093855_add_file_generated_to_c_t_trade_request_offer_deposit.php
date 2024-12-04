<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileGeneratedToCTTradeRequestOfferDeposit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            $table->string('file_pdf_generated')->nullable();
            $table->string('file_csv_generated')->nullable();
            $table->string('file_generated_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            $table->dropColumn('file_pdf_generated');
            $table->dropColumn('file_csv_generated');
            $table->dropColumn('file_generated_count');
        });
    }
}
