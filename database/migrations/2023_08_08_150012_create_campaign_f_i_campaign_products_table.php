<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignFICampaignProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_f_i_campaign_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("campaign_id");
            $table->unsignedBigInteger("fi_campaign_product_id");
            $table->enum("rate_type",["FIXED","INDEX"])->default("FIXED");
            $table->float('index_rate', 8, 2)->default(0.00)->nullable();
            $table->float('spread', 8, 2)->default(0.00)->nullable();
            $table->integer("rate");
            $table->integer("minimum");
            $table->integer("maximum");
            $table->integer("order_limit");
            $table->timestamps();
        });
        Schema::create('campaign_f_i_campaign_products_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("campaign_id");
            $table->unsignedBigInteger("fi_campaign_product_id");
            $table->enum("rate_type",["FIXED","INDEX"])->default("FIXED");
            $table->float('index_rate', 8, 2)->default(0.00)->nullable();
            $table->float('spread', 8, 2)->default(0.00)->nullable();
            $table->integer("rate");
            $table->integer("minimum");
            $table->integer("maximum");
            $table->integer("order_limit");
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
        Schema::dropIfExists('campaign_f_i_campaign_products');
        Schema::dropIfExists('campaign_f_i_campaign_products_archives');
    }
}
