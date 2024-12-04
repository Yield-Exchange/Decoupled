<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsForArchivingToCGTradeRequestInvitedCTOffersArchive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_g_trade_request_invited_c_t_offers_archive', function (Blueprint $table) {
            //
        
            $table->unsignedBigInteger('performed_by');       
            $table->text('prompting_action')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_g_trade_request_invited_c_t_offers_archive', function (Blueprint $table) {
            //
            $table->dropColumn('performed_by');
            $table->dropColumn('prompting_action');
        });
    }
}
