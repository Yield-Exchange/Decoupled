<?php

use Illuminate\Database\Migrations\Migration;

class UpdateStatusOnFICampaignProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `f_i_campaign_products` CHANGE `status` `status` ENUM('ACTIVE','INACTIVE','COMPLETED','EXPIRED') NOT NULL DEFAULT 'ACTIVE';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
