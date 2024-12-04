<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserLimitOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->bigInteger('users_limit')->default(1);
        });

        Schema::table('organizations_archive', function (Blueprint $table) {
            $table->bigInteger('users_limit')->default(1);
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
            $table->dropColumn('users_limit');
        });

        Schema::table('organizations_archive', function (Blueprint $table) {
            $table->dropColumn('users_limit');
        });
    }
}
