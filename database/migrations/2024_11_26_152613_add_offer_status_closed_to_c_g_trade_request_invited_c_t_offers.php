<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddOfferStatusClosedToCGTradeRequestInvitedCTOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
    DB::statement("ALTER TABLE c_g_trade_request_invited_c_t_offers MODIFY COLUMN offer_status ENUM('ACTIVE', 'OFFER_WITHDRAWN', 'EXPIRED', 'SELECTED', 'NOT_SELECTED', 'REQUEST_WITHDRAWN', 'CLOSED_ON_PURCHASE') DEFAULT 'ACTIVE'");
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     
            DB::statement("ALTER TABLE c_g_trade_request_invited_c_t_offers MODIFY COLUMN offer_status ENUM('ACTIVE', 'OFFER_WITHDRAWN', 'EXPIRED', 'SELECTED', 'NOT_SELECTED', 'REQUEST_WITHDRAWN') DEFAULT 'ACTIVE'");
        
    }
}
