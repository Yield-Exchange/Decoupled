<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgPermissionsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_permissions_lists', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("slug");
            $table->string("unenabled_label");
            $table->string("enabled_label");
            $table->enum("permision_status",["ACTIVATED","DEACTIVATED"])->default("ACTIVATED");            
            $table->enum("type",['DEPOSITOR','BANK','UNIVERSAL'])->default('UNIVERSAL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_permissions_lists');
    }
}
