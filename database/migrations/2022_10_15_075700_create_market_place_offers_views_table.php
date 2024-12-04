<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketPlaceOffersViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_place_offers_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('market_place_offer_id');
            $table->unsignedBigInteger('fi_organization_id');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('user_id');
            $table->string('viewed_from_page');
            $table->text('query_string');
            $table->boolean('is_test')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_place_offers_views');
    }
}
