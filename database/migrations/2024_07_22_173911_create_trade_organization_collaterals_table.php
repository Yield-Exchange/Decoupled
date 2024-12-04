<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeOrganizationCollateralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_organization_collaterals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("trade_collateral_id");
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("user_id");
            $table->string("CUSIP_code")->nullable();
            $table->unsignedBigInteger("trade_collateral_issuer_id")->nullable();
            $table->dateTime("maturity_date")->nullable();
            $table->enum("collateral_status",['PENDING','ACTIVE'])->default("PENDING"); 
            $table->softDeletes();
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
        Schema::dropIfExists('trade_organization_collaterals');
    }
}
