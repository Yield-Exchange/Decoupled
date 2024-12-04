<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignProductViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_product_views', function (Blueprint $table) {
            $table->id();
            $table->integer("campaign_product_id");
            $table->integer("viewer_organization_id");
            $table->integer("viewer_user_id");
            $table->boolean("is_test")->default(0);
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
        Schema::dropIfExists('campaign_product_views');
    }
}
