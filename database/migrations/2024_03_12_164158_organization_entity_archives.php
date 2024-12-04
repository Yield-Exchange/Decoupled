<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrganizationEntityArchives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_entity_archives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('organization_entity_id')->nullable();
            $table->string('organization_id')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('organization_type')->nullable();
            $table->string('incorporation_province')->nullable();
            $table->enum('owns_over_twenty_five', ['1', '0', null])->nullable();
            $table->string('percentage_ownership')->nullable();
            $table->string('cra_business_number')->nullable();
            $table->string('inc_business_number')->nullable();
            $table->string('modified_by_user_id')->nullable();
            $table->enum('orperating_for_entity', ['1', '0', null])->nullable();
            $table->string('relationship_with_entity')->nullable();
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
        Schema::dropIfExists('organization_entity_archives');
    }
}
