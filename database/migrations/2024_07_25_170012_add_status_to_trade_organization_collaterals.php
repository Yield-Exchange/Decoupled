<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToTradeOrganizationCollaterals extends Migration
{
    // */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('trade_organization_collaterals', function (Blueprint $table) {
            
            $table->string("rating")->nullable();
           
        });
        DB::statement("ALTER TABLE trade_organization_collaterals MODIFY COLUMN collateral_status ENUM('PENDING','ACTIVE','ARCHIVED','VERIFIED','ATTENTION') DEFAULT 'ACTIVE'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trade_organization_collaterals', function (Blueprint $table) {
            
            $table->dropColumn("rating");

        });
        DB::statement("ALTER TABLE trade_organization_collaterals MODIFY COLUMN collateral_status ENUM('PENDING', 'ACTIVE', 'ARCHIVED','VERIFIED','ATTENTION') DEFAULT 'ACTIVE'");
    }
}
