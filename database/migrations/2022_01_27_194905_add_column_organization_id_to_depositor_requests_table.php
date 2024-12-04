<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOrganizationIdToDepositorRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id');
        });

        Schema::table('depositor_requests_archive', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });

        Schema::table('depositor_requests_archive', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });
    }
}
