<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibilityAdminSettingsOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('show_province_visibility')->default(false);
            $table->boolean('show_naics_codes_visibility')->default(false);
            $table->boolean('show_customers_visibility')->default(false);
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
            $table->dropColumn('show_province_visibility');
            $table->dropColumn('show_naics_codes_visibility');
            $table->dropColumn('show_customers_visibility');
        });
    }
}
