<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnLockoutPeriodOnMarketOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_place_offers', function (Blueprint $table) {
            $table->integer('lockout_period')->nullable()->change();
        });
        Schema::table('market_place_offers_archives', function (Blueprint $table) {
            $table->integer('lockout_period')->nullable()->change();
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
            $table->integer('lockout_period')->nullable($value = false)->change();
        }); 
        Schema::table('market_place_offers_archives', function (Blueprint $table) {
            $table->integer('lockout_period')->nullable($value = false)->change();
        });
    }
}
