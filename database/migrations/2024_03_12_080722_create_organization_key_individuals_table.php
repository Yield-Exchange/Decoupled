<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationKeyIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_key_individuals', function (Blueprint $table) {
            $table->id();
            $table->string('organization_id');
            $table->string('user_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('job_title')->nullable();
            $table->enum('is_director', ['1', '0', null])->nullable();
            $table->enum('owns_over_twenty_five', ['1', '0', null])->nullable();
            $table->string('percentage_ownership')->nullable();
            $table->enum('is_signingofficer', ['1', '0', null])->nullable();
            $table->enum('is_politicallyexposed', ['1', '0', null])->nullable();
            $table->enum('is_actingonattorneypower', ['1', '0', null])->nullable();
            $table->enum('orperating_for_entity', ['1', '0', null])->nullable();
            $table->enum('operating_for_corporation', ['1', '0', null])->nullable();
            $table->string('relationship_with_corporation')->nullable();
            $table->string('modified_section')->nullable();
            $table->timestamp('deleted_at')->nullable();

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
        Schema::dropIfExists('organization_key_individuals');
    }
}
