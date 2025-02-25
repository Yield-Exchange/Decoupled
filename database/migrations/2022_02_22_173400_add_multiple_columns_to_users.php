<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();

        });

        Schema::table('users_archive', function (Blueprint $table) {
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firstname','lastname']);
        });

         Schema::table('users_archive', function (Blueprint $table) {
            $table->dropColumn(['firstname','lastname']);
        });
    }
}
