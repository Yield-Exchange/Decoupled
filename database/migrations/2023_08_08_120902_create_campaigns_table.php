<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string("campaign_name");
            $table->dateTime("expiry_date");
            $table->dateTime("start_date");
            $table->String("currency");
            $table->enum("status",["ACTIVE","EXPIRED","COMPLETED","INACTIVE","DRAFT"])->default("DRAFT");
            $table->unsignedBigInteger("fi_id");
            $table->unsignedBigInteger("created_by");
            $table->integer("subscription_amount")->default(0);
            $table->string("description")->nullable();
            $table->timestamps();
        });
        Schema::create('campaigns_archives', function (Blueprint $table) {
            $table->id();
            $table->string("campaign_name");
            $table->dateTime("expiry_date");
            $table->dateTime("start_date");
            $table->String("currency");
            $table->enum("status",["ACTIVE","EXPIRED","COMPLETED","INACTIVE","DRAFT"])->default("DRAFT");
            $table->unsignedBigInteger("fi_id");
            $table->unsignedBigInteger("created_by");
            $table->integer("subscription_amount")->default(0);
            $table->string("description")->nullable();
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
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('campaigns_archives');
    }
}
