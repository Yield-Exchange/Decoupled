<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductDisclosureColumnsToMarketPlaceOffersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_place_offers', function (Blueprint $table) {
            $table->string('product_disclosure_statement')->nullable();
            $table->string('product_disclosure_url')->nullable();
        });
        Schema::table('market_place_offers_archives', function (Blueprint $table) {
            $table->string('product_disclosure_statement')->nullable();
            $table->string('product_disclosure_url')->nullable();
        });
    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_place_offers', function (Blueprint $table) {
            $table->dropColumn('product_disclosure_statement');
            $table->dropColumn('product_disclosure_url');
        });
        Schema::table('market_place_offers_archives', function (Blueprint $table) {
            $table->dropColumn('product_disclosure_statement');
            $table->dropColumn('product_disclosure_url');
        });
    }
}
