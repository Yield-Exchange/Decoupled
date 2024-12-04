<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateStatusToRejectIfCounterOfferRejected extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::statement("ALTER TABLE `offers` CHANGE `offer_status` `offer_status` ENUM('ACTIVE','OFFER_WITHDRAWN','EXPIRED','SELECTED','NOT_SELECTED','REQUEST_WITHDRAWN','DECLINED') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;");
      DB::statement("ALTER TABLE `depositor_requests` CHANGE `request_status` `request_status` ENUM('WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','NO_OFFERS_RECEIVED','ON_REVIEW','DECLINED') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('reject_if_counter_offer_rejected', function (Blueprint $table) {
        //     //
        // });
    }
}
