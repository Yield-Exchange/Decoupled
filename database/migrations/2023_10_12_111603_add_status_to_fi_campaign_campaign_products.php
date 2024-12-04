<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFiCampaignCampaignProducts extends Migration
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
            $table->enum("status", ["ACTIVE", "EXPIRED", "COMPLETED", "INACTIVE", "DRAFT"])->default("DRAFT");
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
            $table->dropColumn('status');
        });
    }
}
