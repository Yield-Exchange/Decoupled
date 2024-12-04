<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTFileHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t_file_headers', function (Blueprint $table) {
            $table->id();
            $table->enum('mt_file_type',['MT569','MT558','MT527'])->default('MT569');
            $table->unsignedBigInteger('m_t_general_info_id');
            $table->string('sender_swift_code')->nullable();
            $table->string('session_sequence_numbers')->nullable();
            $table->time('time_sent')->nullable();
            $table->date('date_sent')->nullable();
            $table->string('receiver_swift_code')->nullable();
            $table->string('message_priority')->nullable();
            $table->dateTime('date_time_of_message_referenced')->nullable();
            $table->string('delivery_status')->nullable();
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
        Schema::dropIfExists('m_t_file_headers');
    }
}
