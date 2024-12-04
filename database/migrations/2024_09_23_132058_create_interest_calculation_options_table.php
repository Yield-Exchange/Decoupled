<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterestCalculationOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interest_calculation_options', function (Blueprint $table) {
            $table->id();
            $table->string("label");
            $table->string("slug");
            $table->text("description")->nullable();
            $table->integer("used_no_of_days_in_a_non_leap_year")->default(365);
            $table->integer("used_no_of_days_in_a_leap_year")->default(366);
            $table->string("formula")->nullable();
            $table->enum("status",['ACTIVE','INACTIVE'])->default("ACTIVE");
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
        Schema::dropIfExists('interest_calculation_options');
    }
}
