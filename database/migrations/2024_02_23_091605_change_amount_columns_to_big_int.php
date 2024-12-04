<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAmountColumnsToBigInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_f_i_campaign_products', function (Blueprint $table) {
            $table->bigInteger('minimum')->nullable()->change();
            $table->bigInteger('maximum')->nullable()->change();
            $table->bigInteger('order_limit')->nullable()->change();
        });
        Schema::table('campaigns', function (Blueprint $table) {
            $table->bigInteger('subscription_amount')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_f_i_campaign_products', function (Blueprint $table) {
            //
        });
        Schema::table('campaigns', function (Blueprint $table) {
            
        });
    }
}
