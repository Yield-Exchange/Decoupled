<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class UpdateStatusInvitedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `invited` CHANGE `invitation_status` `invitation_status` ENUM('INVITED','UNINVITED','PARTICIPATED','DID_NOT_PARTICIPATE','ON_REVIEW')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `invited` CHANGE `invitation_status` `invitation_status` ENUM('INVITED','UNINVITED','PARTICIPATED','DID_NOT_PARTICIPATE')");
    }
}
