<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibilityColumnsOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('visible_for_provinces')->nullable();
            $table->string('visible_for_customers')->nullable();
            $table->string('visible_for_naics_codes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('visible_for_provinces');
            $table->dropColumn('visible_for_provinces');
            $table->dropColumn('visible_for_naics_codes');
        });
    }
}
