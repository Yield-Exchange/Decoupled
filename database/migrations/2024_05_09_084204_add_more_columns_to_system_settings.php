<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToSystemSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_settings', function (Blueprint $table) {        
            $table->string("setting_type")->nullable();
            $table->text("description")->nullable();
            $table->string("rate_label")->nullable();
            $table->string("long_form")->nullable();
            $table->enum("status",['ACTIVE','INACTIVE'])->default('ACTIVE');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropColumn("setting_type");
            $table->dropColumn("description");
            $table->dropColumn("rate_label");
            $table->dropColumn("long_form");
            $table->dropColumn('status');
        });
    }
}
