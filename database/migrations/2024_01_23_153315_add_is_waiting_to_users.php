<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsWaitingToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('is_waiting', ['PERSONAL_DEPOSITOR', 'NOT_IN_TIMEZONE', 'MODULE','KEEP_ME_INFORMED','DECLINE_TERMS'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'is_waiting')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_waiting');
            });
        }
    }
}
