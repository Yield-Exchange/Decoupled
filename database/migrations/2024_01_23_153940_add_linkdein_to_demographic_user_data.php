<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkdeinToDemographicUserData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demographic_user_data', function (Blueprint $table) {
            $table->string('linkedin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('demographic_user_data', 'linkedin')) {
            Schema::table('demographic_user_data', function (Blueprint $table) {
                $table->dropColumn('linkedin');
            });
        }
    }
}
