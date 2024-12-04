<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCumulativeTotalColumnToMarketPlaceOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_place_offers', function (Blueprint $table) {
            $table->decimal('cumulative_total', 20)->nullable()->after('maximum_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_place_offers', function (Blueprint $table) {
            $table->dropColumn('cumulative_total');
        });
    }
}
