<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduledToCampaignFiCampignProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `campaign_f_i_campaign_products` CHANGE `status` `status` ENUM('ACTIVE', 'EXPIRED', 'COMPLETED', 'INACTIVE', 'DRAFT','SCHEDULED') NOT NULL DEFAULT 'DRAFT';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
