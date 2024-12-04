<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsWaitingToOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->enum('is_waiting', ['PERSONAL_DEPOSITOR', 'NOT_IN_TIMEZONE', 'MODULE','DECLINE_TERMS'])->nullable();
            $table->enum('registration_type',['PARTTNERSHIP','SOLE','CORPORATION','CROWN'])->nullable();
            $table->string('trade_name')->nullable();
            $table->string('incoporation_number')->nullable();
            $table->string('CRA_business_number')->nullable();
            $table->dateTime('incoporation_date')->nullable();
            $table->string('province_of_incorporation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
