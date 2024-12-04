<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletionStatusToFICampaignGroupps extends Migration
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
            $table->enum('group_deletion_status',['ACTIVE','INACTIVE'])->default('ACTIVE');
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
            $table->dropColumn('group_deletion_status');
        });
    }
}
