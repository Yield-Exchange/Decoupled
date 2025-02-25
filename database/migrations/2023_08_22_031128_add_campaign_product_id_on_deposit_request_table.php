<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampaignProductIdOnDepositRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger("campaign_product_id")->nullable();
        });
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->unsignedBigInteger("campaign_product_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn("campaign_product_id");
        });
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->dropColumn("campaign_product_id");
        });
    }
}
