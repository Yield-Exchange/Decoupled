<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCreditRatingTypeAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_rating_type', function (Blueprint $table) {
            $table->enum("status",["ACTIVE","INACTIVE"])->default("ACTIVE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_rating_type', function (Blueprint $table) {
            $table->dropColumn("status");
        });
    }
}
