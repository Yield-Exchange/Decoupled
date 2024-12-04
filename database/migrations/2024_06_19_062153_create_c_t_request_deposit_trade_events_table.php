<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCTRequestDepositTradeEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_t_request_deposit_trade_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("c_t_trade_request_offer_deposit_id");
            $table->unsignedBigInteger("initiating_organization_id");
            $table->unsignedBigInteger("receiving_organization_id");
            $table->unsignedBigInteger("initiating_user_id");
            $table->unsignedBigInteger("approving_id")->nullable();
            $table->enum("event_type", ["early_termination", "extension", "rate_change", "increase_exposure", "decrease_exposure"]);
            $table->enum("event_status", ['INITIATED','AWAITING_TRADING_PARTY_APPROVAL','TRADING_PARTY_DECLINED','AWAITING_THIRD_PARTY_APPROVAL','THIRD_PARTY_DECLINED','COMPLETED','CLOSED_ON_NEW_EVENT']);
            $table->dateTime("old_maturity_date")->nullable();
            $table->dateTime("new_maturity_date")->nullable();
            $table->string("termination_reason")->nullable();
            $table->double("new_rate", 15, 2)->default(0.00);
            $table->double("old_rate", 15, 2)->default(0.00);
            $table->bigInteger("old_purchase_value")->nullable();
            $table->bigInteger("new_purchase_value")->nullable();
            $table->text("special_notes")->nullable();
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
        Schema::dropIfExists('c_t_request_deposit_trade_events');
    }
}
