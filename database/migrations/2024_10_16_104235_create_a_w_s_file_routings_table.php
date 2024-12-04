<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAWSFileRoutingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_w_s_file_routings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->enum('file_type', ['pdf', 'csv'])->default('csv');
            $table->enum('routing_agent', ['cds', 'self', 'yield'])->default('self');
            $table->enum('delivery_method', ['email', 'file_transfer'])->default('file_transfer');
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
        Schema::dropIfExists('a_w_s_file_routings');
    }
}
