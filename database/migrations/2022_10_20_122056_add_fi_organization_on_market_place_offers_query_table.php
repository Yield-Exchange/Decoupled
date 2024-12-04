<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiOrganizationOnMarketPlaceOffersQueryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_place_offers_queries', function (Blueprint $table) {
            $table->unsignedBigInteger('fi_organization_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_place_offers_queries', function (Blueprint $table) {
            $table->dropColumn('fi_organization_id');
        });
    }
}
