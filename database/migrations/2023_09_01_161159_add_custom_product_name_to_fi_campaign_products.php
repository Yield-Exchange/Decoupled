<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomProductNameToFiCampaignProducts extends Migration
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
            $table->string("custom_product_name")->nullable();
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
            $table->dropColumn("custom_product_name");
        });
    }
}
