<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_entities', function (Blueprint $table) {
            $table->id();
            $table->string('organization_id');
            $table->string('organization_name');
            $table->string('organization_type')->nullable();
            $table->string('incorporation_province')->nullable();
            $table->enum('owns_over_twenty_five', ['1', '0', null])->nullable();
            $table->string('percentage_ownership')->nullable();
            $table->string('cra_business_number')->nullable();
            $table->string('inc_business_number')->nullable();
            $table->enum('orperating_for_entity', ['1', '0', null])->nullable();
            $table->string('relationship_with_entity')->nullable();
            $table->string('modified_section')->nullable();
            $table->string('deleted_at')->nullable();
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
        Schema::dropIfExists('organization_entities');
    }
}
