<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTradeOrganizationCollateral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_organization_collaterals', function (Blueprint $table) {
            $table->dropColumn([
                'CUSIP_code',
                'maturity_date',
                'is_dummy',
                'trade_collateral_id',
            ]);
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
            $table->string('CUSIP_code');
            $table->date('maturity_date');
            $table->boolean('is_dummy');
            $table->unsignedBigInteger('trade_collateral_id');
        });
    }
}
