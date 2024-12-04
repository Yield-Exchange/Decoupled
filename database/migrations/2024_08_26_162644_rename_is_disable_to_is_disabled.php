<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameIsDisableToIsDisabled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_collaterals', function (Blueprint $table) {
            //
            $table->renameColumn("is_disable","is_disabled");
        });
        Schema::table('trade_basket_types', function (Blueprint $table) {
            //
            $table->renameColumn("disabled","is_disabled");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trade_collaterals', function (Blueprint $table) {
            //
            $table->renameColumn("is_disabled","is_disable");
        });
        Schema::table('trade_basket_types', function (Blueprint $table) {
            //
            $table->renameColumn("is_disabled","disabled");
        });
    }
}
