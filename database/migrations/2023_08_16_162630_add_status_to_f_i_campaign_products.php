<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFICampaignProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('f_i_campaign_products', function (Blueprint $table) {
            //
            $table->enum("status",['ACTIVE','INACTIVE'])->default("ACTIVE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('f_i_campaign_products', function (Blueprint $table) {
            //
            $table->dropColumn("status");
        });
    }
}
