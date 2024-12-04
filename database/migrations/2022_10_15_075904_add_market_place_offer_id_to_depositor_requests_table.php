<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarketPlaceOfferIdToDepositorRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('market_place_offer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->dropColumn('market_place_offer_id');
        });
    }
}
