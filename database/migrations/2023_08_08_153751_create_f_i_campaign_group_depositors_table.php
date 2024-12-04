<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFICampaignGroupDepositorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_i_campaign_group_depositors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fi_campaign_group_id");
            $table->unsignedBigInteger("depositor_id");
            $table->timestamps();
        });
        Schema::create('f_i_campaign_group_depositors_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fi_campaign_group_id");
            $table->unsignedBigInteger("depositor_id");
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
        Schema::dropIfExists('f_i_campaign_group_depositors');
        Schema::dropIfExists('f_i_campaign_group_depositors_archives');
    }
}
