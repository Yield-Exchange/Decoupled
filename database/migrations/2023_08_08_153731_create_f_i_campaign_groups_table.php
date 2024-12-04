<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFICampaignGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_i_campaign_groups', function (Blueprint $table) {
            $table->id();
            $table->string("group_name");
            $table->unsignedBigInteger("fi_id");
            $table->unsignedBigInteger("created_by");
            $table->text("description")->nullable();
            $table->timestamps();
          
        });
        Schema::create('f_i_campaign_groups_archives', function (Blueprint $table) {
            $table->id();
            $table->string("group_name");
            $table->unsignedBigInteger("fi_id");
            $table->unsignedBigInteger("created_by");
            $table->text("description")->nullable();
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
        Schema::dropIfExists('f_i_campaign_groups');
        Schema::dropIfExists('f_i_campaign_groups_archives');
    }
}
