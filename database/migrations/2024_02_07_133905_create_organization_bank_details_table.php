<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('organization_id');
            $table->string('transit_code');
            $table->string('cibcinstitutionnumber');
            $table->string('clearingcode');
            $table->string('beneficiary_acc_number')->nullable();
            $table->string('beneficiary_name')->nullable();
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
        Schema::dropIfExists('organization_bank_details');
    }
}
