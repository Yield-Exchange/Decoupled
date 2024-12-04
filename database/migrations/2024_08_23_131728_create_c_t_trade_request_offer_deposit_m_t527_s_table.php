<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCTTradeRequestOfferDepositMT527STable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_t_trade_request_offer_deposit_m_t527_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archive_id')->nullable();
            $table->string('mt_527_sender_reference')->nullable();
            $table->enum('mt_558_status',['PENDING','RECEIVED'])->default('PENDING');
            $table->unsignedBigInteger('m_t558_generals_id')->nullable();
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
        Schema::dropIfExists('c_t_trade_request_offer_deposit_m_t527_s');
    }
}
