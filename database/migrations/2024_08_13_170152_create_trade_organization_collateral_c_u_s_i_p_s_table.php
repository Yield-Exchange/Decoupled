<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeOrganizationCollateralCUSIPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_organization_collateral_c_u_s_i_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('CUSIP_code');
            $table->date('maturity_date')->nullable();
            $table->enum("cusips_status",['PENDING','ACTIVE','ARCHIVED','VERIFIED','ATTENTION'])->default("ACTIVE"); 
            $table->boolean('is_dummy');
            $table->unsignedBigInteger('trade_organization_collateral_id');
            $table->unsignedBigInteger('trade_collateral_id');
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
        Schema::dropIfExists('trade_organization_collateral_c_u_s_i_p_s');
    }
}
