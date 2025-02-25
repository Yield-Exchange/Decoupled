<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteFlagToCampaignFICampaignProducts extends Migration
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
            $table->dateTime("deleted_at")->nullable();
            $table->boolean("isFeatured")->default(0);
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
            $table->dropColumn("deleted_at");
            $table->dropColumn("isFeatured");            
        });
    }
}
