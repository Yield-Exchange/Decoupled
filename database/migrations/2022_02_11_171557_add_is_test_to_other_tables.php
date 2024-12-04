<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsTestToOtherTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });
        Schema::table('chat', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });
        Schema::table('depositor_requests_archive', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });
        Schema::table('deposits', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });
        Schema::table('deposits_archive', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });
        Schema::table('invited', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });
        Schema::table('login_activities', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
        });

        Schema::table('offers_archives', function (Blueprint $table) {
            $table->boolean("is_test")->default(false);
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
            $table->dropColumn('is_test');
        });
        Schema::table('chat', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
        Schema::table('depositor_requests', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
        Schema::table('depositor_requests_archive', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
        Schema::table('deposits_archive', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
        Schema::table('invited', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
        Schema::table('login_activities', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });

        Schema::table('offers_archives', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
    }
}
