<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAmountColumnOnMarketPlaceOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_place_offers', function (Blueprint $table) {
            $table->decimal('minimum_amount',20,2)->change();
            $table->decimal('maximum_amount',20,2)->change();
        });
        Schema::table('market_place_offers_archives', function (Blueprint $table) {
            $table->decimal('minimum_amount',20,2)->change();
            $table->decimal('maximum_amount',20,2)->change();
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
            $table->decimal('minimum_amount')->change();
            $table->decimal('maximum_amount')->change();
        });
        Schema::table('market_place_offers_archives', function (Blueprint $table) {
            $table->decimal('minimum_amount')->change();
            $table->decimal('maximum_amount')->change();
        });
    }
}
