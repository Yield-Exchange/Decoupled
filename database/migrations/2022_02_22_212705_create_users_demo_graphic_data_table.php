<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDemoGraphicDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demographic_user_data', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->string('job_title')->nullable();
            $table->string('timezone')->nullable();
            $table->string('department')->nullable();
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
        Schema::dropIfExists('demographic_user_data');
    }
}
