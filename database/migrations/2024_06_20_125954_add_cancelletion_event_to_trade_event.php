<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelletionEventToTradeEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $thevalues =["early_termination", "extension", "rate_change","cancelletion", "increase_exposure", "decrease_exposure"];


        $enumValuesString = "'" . implode("', '", $thevalues) . "'";
        DB::statement("ALTER TABLE c_t_request_deposit_trade_events MODIFY COLUMN event_type ENUM($enumValuesString) NOT NULL");


        $thevalues = [
            'PENDING_DEPOSIT',
            'INCOMPLETE',
            'EARLY_REDEMPTION',
            'ACTIVE',
            'MATURED',
            'WITHDRAWN',
            'INITIATED',
            'AWAITING_TRADING_PARTY_APPROVAL',
            'TRADING_PARTY_DECLINED',
            'AWAITING_THIRD_PARTY_APPROVAL',
            'THIRD_PARTY_DECLINED', 
            'COMPLETED'
        ];

        $enumValuesString = "'" . implode("', '", $thevalues) . "'";
        DB::statement("ALTER TABLE c_t_request_deposit_trade_events MODIFY COLUMN event_status ENUM($enumValuesString) NOT NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $thevalues = ["early_termination", "extension", "rate_change", "increase_exposure", "decrease_exposure"];

        $enumValuesString = "'" . implode("', '", $thevalues) . "'";
        DB::statement("ALTER TABLE c_t_request_deposit_trade_events MODIFY COLUMN event_type ENUM($enumValuesString) NOT NULL");


        $thevalues = [
            'PENDING_DEPOSIT',
            'INCOMPLETE',
            'EARLY_REDEMPTION',
            'ACTIVE',
            'MATURED',
            'WITHDRAWN'
        ];

        $enumValuesString = "'" . implode("', '", $thevalues) . "'";
        DB::statement("ALTER TABLE c_t_request_deposit_trade_events MODIFY COLUMN event_status ENUM($enumValuesString) NOT NULL");
    }
}
