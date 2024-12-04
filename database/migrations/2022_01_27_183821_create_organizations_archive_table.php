<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations_archive', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type',['BANK', 'DEPOSITOR', 'BOTH']);
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->boolean('is_non_partnered_fi')->default(false);
            $table->string('logo')->nullable();
            $table->enum('status',['REVIEWING','DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS','INVITED','PENDING','ACTIVE','INACTIVE']);
            $table->string('modified_section')->nullable();
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
        Schema::dropIfExists('organizations_archive');
    }
}
