<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountClosureColumnOnOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('organizations', function (Blueprint $table) {
            $table->dateTime('account_closure_date')->nullable();
            $table->string('account_closure_reason')->nullable();
        });

        Schema::table('organizations_archive', function (Blueprint $table) {
            $table->dateTime('account_closure_date')->nullable();
            $table->string('account_closure_reason')->nullable();
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
            $table->dropColumn('account_closure_date');
            $table->dropColumn('account_closure_reason');
        });

        Schema::table('organizations_archive', function (Blueprint $table) {
            $table->dropColumn('account_closure_date');
            $table->dropColumn('account_closure_reason');
        });
    }
}
