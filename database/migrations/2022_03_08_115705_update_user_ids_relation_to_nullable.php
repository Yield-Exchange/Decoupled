<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateUserIdsRelationToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `credit_rating` MODIFY `user_id` BIGINT UNSIGNED NULL;');
        DB::statement('ALTER TABLE `login_activities` MODIFY `user_id` BIGINT UNSIGNED NULL;');
        DB::statement('ALTER TABLE `activity_logs` MODIFY `user_id` BIGINT UNSIGNED NULL;');
        DB::statement('ALTER TABLE `demographic_organization_data` MODIFY `user_id` BIGINT UNSIGNED NULL;');
        DB::statement('ALTER TABLE `notifications` MODIFY `user_id` BIGINT UNSIGNED NULL;');
        DB::statement('ALTER TABLE `chat` MODIFY `sent_by` BIGINT UNSIGNED NULL;');
        DB::statement('ALTER TABLE `chat` MODIFY `sent_to` BIGINT UNSIGNED NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `credit_rating` MODIFY `user_id` BIGINT UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE `login_activities` MODIFY `user_id` BIGINT UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE `activity_logs` MODIFY `user_id` BIGINT UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE `demographic_organization_data` MODIFY `user_id` BIGINT UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE `notifications` MODIFY `user_id` BIGINT UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE `chat` MODIFY `sent_by` BIGINT UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE `chat` MODIFY `sent_to` BIGINT UNSIGNED NOT NULL;');
    }
}
