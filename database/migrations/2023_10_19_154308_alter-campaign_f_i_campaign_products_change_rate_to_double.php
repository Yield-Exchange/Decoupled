<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCampaignFICampaignProductsChangeRateToDouble extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_f_i_campaign_products', function (Blueprint $table) {
            //
            $table->decimal('rate', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_f_i_campaign_products', function (Blueprint $table) {
            //
            $table->integer('rate')->change();
        });
    }
}
