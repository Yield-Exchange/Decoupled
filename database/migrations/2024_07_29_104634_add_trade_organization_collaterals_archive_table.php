<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTradeOrganizationCollateralsArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_organization_collaterals_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("trade_organization_collateral_id");
            $table->unsignedBigInteger("trade_collateral_id");
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("user_id");
            $table->string("CUSIP_code")->nullable();
            $table->unsignedBigInteger("trade_collateral_issuer_id")->nullable();
            $table->dateTime("maturity_date")->nullable();
            $table->enum("collateral_status",['PENDING','ACTIVE','ARCHIVED','VERIFIED','ATTENTION'])->default("PENDING"); 
            $table->softDeletes();
            $table->unsignedBigInteger("performed_by")->nullable();
            $table->string("prompting_action")->nullable();
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
        Schema::dropIfExists('trade_organization_collaterals_archive');
    }
}
