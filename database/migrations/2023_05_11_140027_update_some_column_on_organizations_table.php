<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSomeColumnOnOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demographic_organization_data', function (Blueprint $table) {
            $table->string('address1')->nullable()->change();
            $table->string('address2')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('province')->nullable()->change();
            $table->string('postal_code')->nullable()->change();
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->string('needs_update')->default('yes')->nullable();
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
            $table->string('address1')->nullable(false)->change();
            $table->string('address2')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('province')->nullable(false)->change();
            $table->string('postal_code')->nullable(false)->change();
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('needs_update');
        });
    }
}
