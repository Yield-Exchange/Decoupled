<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampaignInviteTypeToGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('f_i_campaign_groups', function (Blueprint $table) {
            //
            $table->enum('group_type',['Generated','User Created'])->default('User Created');
        });
        Schema::table('f_i_campaign_groups_archives', function (Blueprint $table) {
            //
            $table->enum('group_type',['Generated','User Created'])->default('User Created');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('f_i_campaign_groups', function (Blueprint $table) {
            //
            $table->dropColumn('group_type');
        });
        Schema::table('f_i_campaign_groups_archives', function (Blueprint $table) {
            //
            $table->dropColumn('group_type');
        });
    }
}
