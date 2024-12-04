<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDummyToTradeTradeOrganizationCollaterals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_organization_collaterals', function (Blueprint $table) {
            $table->boolean("is_dummy")->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trade_organization_collaterals', function (Blueprint $table) {
            $table->dropColumn("is_dummy");
        });
        
    }
}
