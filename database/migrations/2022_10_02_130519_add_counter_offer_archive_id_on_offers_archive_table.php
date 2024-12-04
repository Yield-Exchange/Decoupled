<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCounterOfferArchiveIdOnOffersArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers_archives', function (Blueprint $table) {
            $table->unsignedBigInteger('counter_offer_archive_id')->nullable();
            $table->text('offer_withdrawal_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers_archives', function (Blueprint $table) {
            $table->dropColumn('counter_offer_archive_id');
            $table->dropColumn('offer_withdrawal_reason');
        });
    }
}
