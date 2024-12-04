<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignupTemporaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signup_temporary_data', function (Blueprint $table) {
            $table->id();
            $table->string('verification_code');
            $table->string('email');
            $table->boolean('verified')->default(false);
            $table->string('ip_address')->nullable();
            $table->json('request_data');
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
        Schema::dropIfExists('signup_temporary_data');
    }
}
