<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT558LinkagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t558_linkages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t558_general_id');
            $table->string("related_message_reference")->comment("Related message reference (20C RELA)")->nullable();
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
        Schema::dropIfExists('m_t558_linkages');
    }
}
