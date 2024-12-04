<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSummaryColumnToOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demographic_organization_data', function (Blueprint $table) {
            $table->text('summary')->nullable();
            $table->enum('seen_summary', ['yes', 'no'])->default('no');
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
            $table->dropColumn('summary');
            $table->dropColumn('seen_summary');
        });
    }
}
