<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrganizationsArchiveToOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_archive', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_temporary')->default(0);
            $table->string('account_manager')->nullable();
            $table->string('inviter_name')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->boolean('is_test')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations_archive', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('is_temporary');
            $table->dropColumn('account_manager');
            $table->dropColumn('inviter_name');
            $table->dropColumn('modified_by');
            $table->dropColumn('is_test');
        });
    }
}
