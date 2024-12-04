<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCampaignProductIdColumnToCampaignFICampaignProductId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_product_views', function (Blueprint $table) {
                $table->renameColumn('campaign_product_id', 'campaign_f_i_campaign_product_id');          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_product_views', function (Blueprint $table) {
            //
        });
    }
}
