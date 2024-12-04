<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTradeEventStatusToCTTradeRequestOfferDeposits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

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
        DB::statement("ALTER TABLE c_t_trade_request_offer_deposits MODIFY COLUMN deposit_status ENUM($enumValuesString) NOT NULL");
        

        Schema::table('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            $table->enum("active_trate_event",["early_termination","extension","rate_change","increase_exposure","decrease_exposure"])->nullable();
        });
        


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $thevalues = [
            'PENDING_DEPOSIT',
            'INCOMPLETE',
            'EARLY_REDEMPTION',
            'ACTIVE',
            'MATURED',
            'WITHDRAWN'
        ];


        $enumValuesString = "'" . implode("', '", $thevalues) . "'";
        DB::statement("ALTER TABLE c_t_trade_request_offer_deposits MODIFY COLUMN deposit_status ENUM($enumValuesString) NOT NULL");
        

        Schema::table('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            $table->dropColumn("active_trate_event");
        });

        
    }
}
