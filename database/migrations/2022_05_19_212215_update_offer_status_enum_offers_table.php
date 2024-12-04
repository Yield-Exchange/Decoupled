<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOfferStatusEnumOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE offers CHANGE COLUMN offer_status offer_status ENUM('ACTIVE','OFFER_WITHDRAWN','EXPIRED','SELECTED','NOT_SELECTED','REQUEST_WITHDRAWN') NOT NULL ");
        DB::statement("ALTER TABLE offers_archives CHANGE COLUMN offer_status offer_status ENUM('ACTIVE','OFFER_WITHDRAWN','EXPIRED','SELECTED','NOT_SELECTED','REQUEST_WITHDRAWN') NOT NULL ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
