<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminLoggedinAsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_loggedin_as')
                ->nullable()
                ->default(NULL);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_loggedin_as')
                ->nullable()
                ->default(NULL);
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_loggedin_as')
                ->nullable()
                ->default(NULL);
        });
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_loggedin_as')
                ->nullable()
                ->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('admin_loggedin_as');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin_loggedin_as');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('admin_loggedin_as');
        });
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->dropColumn('admin_loggedin_as');
        });
    }
}
