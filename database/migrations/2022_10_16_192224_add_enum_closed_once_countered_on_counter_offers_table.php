<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnumClosedOnceCounteredOnCounterOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE counter_offers CHANGE COLUMN status status ENUM('ACCEPTED','DECLINED','COUNTERED','PENDING','EXPIRED','EDITED','CLOSED_ON_COUNTERED','CLOSED_ON_OFFER_SELECTION') DEFAULT  'PENDING'");
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
