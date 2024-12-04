<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyOnMarketPlaceOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_place_offers', function (Blueprint $table) {
            $table->string('currency')->nullable();
        });
        Schema::table('market_place_offers_archives', function (Blueprint $table) {
            $table->string('currency')->nullable();
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
            $table->dropColumn('currency');
        });
        Schema::table('market_place_offers_archives', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
}
