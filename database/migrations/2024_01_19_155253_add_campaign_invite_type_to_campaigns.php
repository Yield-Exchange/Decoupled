<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampaignInviteTypeToCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            //
            $table->enum('campaign_depositors_invite_type',['Blanket','Targeted'])->default('Blanket');
        });
        Schema::table('campaigns_archives', function (Blueprint $table) {
            //
            $table->enum('campaign_depositors_invite_type',['Blanket','Targeted'])->default('Blanket');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            //
            $table->dropColumn('campaign_depositors_invite_type');
        });
        Schema::table('campaigns_archives', function (Blueprint $table) {
            //
            $table->dropColumn('campaign_depositors_invite_type');
        });
    }
}
