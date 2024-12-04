<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignTargetGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_target_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fi_campaign_group_id");
            $table->unsignedBigInteger("campaign_id");
            $table->timestamps();
        });
        Schema::create('campaign_target_groups_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fi_campaign_group_id");
            $table->unsignedBigInteger("campaign_id");
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
        Schema::dropIfExists('campaign_target_groups');
        Schema::dropIfExists('campaign_target_groups_archives');
    }
}
