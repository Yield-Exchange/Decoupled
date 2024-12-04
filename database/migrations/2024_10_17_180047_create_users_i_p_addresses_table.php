<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersIPAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_i_p_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->dateTime("logged_in_at");
            $table->dateTime("logged_out_at")->nullable();
            $table->string("logged_ip")->nullable();
            $table->string("login_as_admin_token")->nullable();
            $table->enum("status",['ACTIVE','EXPIRED'])->default("ACTIVE");
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
        Schema::dropIfExists('users_i_p_addresses');
    }
}
