<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationTypeToPermissionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions_group', function (Blueprint $table) {
            $table->enum('user_group',['BANK', 'DEPOSITOR', 'ALL']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions_group', function (Blueprint $table) {
            $table->dropColumn('user_group');
        });
    }
}
