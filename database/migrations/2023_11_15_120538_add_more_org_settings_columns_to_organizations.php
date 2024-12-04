<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreOrgSettingsColumnsToOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demographic_organization_data', function (Blueprint $table) {
            $table->integer("no_of_branches")->nullable();
            $table->integer("value_of_assets")->nullable();
            $table->integer("year_of_establishment")->nullable();
            $table->integer("short_term_DBRS_rating_id")->nullable();
            $table->string("org_email")->nullable();
            $table->text("org_bio")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demographic_organization_data', function (Blueprint $table) {
            //
            $table->dropColumn("no_of_branches");
            $table->dropColumn("value_of_assets");
            $table->dropColumn("year_of_establishment");
            $table->dropColumn("short_term_DBRS_rating_id");
            $table->dropColumn("org_email");
            $table->dropColumn("org_bio");
        });
    }
}
