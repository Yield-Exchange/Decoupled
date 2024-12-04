<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgRequestAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_request_accesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("org_permissions_list_id");
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("user_id");
            $table->enum("status",['PENDING','APPROVED','DECLINED','CANCELLED'])->default('PENDING');
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
        Schema::dropIfExists('org_request_accesses');
    }
}
