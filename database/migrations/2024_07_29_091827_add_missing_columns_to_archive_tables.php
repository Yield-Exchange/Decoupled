<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToArchiveTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('c_t_trade_requests_archive', function (Blueprint $table) {
            $table->text("request_withdrawal_reason")->nullable();
            $table->unsignedBigInteger("performed_by")->nullable();
            $table->string("prompting_action")->nullable();
        });
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            $table->text("rate_operator")->nullable();
            $table->unsignedBigInteger("performed_by")->nullable();
            $table->string("prompting_action")->nullable();
        });
        Schema::table('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            $table->renameColumn("active_trate_event","active_trade_event")->nullable();
        });
        Schema::table('c_t_trade_request_offer_deposits_archive', function (Blueprint $table) {
            $table->text("active_trade_event")->nullable();
            $table->text("active_trade_events_batch_number")->nullable();
            $table->unsignedBigInteger("performed_by")->nullable();
            $table->string("prompting_action")->nullable();
        });
        Schema::table('trade_basket_types_archive', function (Blueprint $table) {         
            $table->renameColumn('baket_type_id', 'basket_type_id');
            $table->unsignedBigInteger("performed_by")->nullable();
            $table->string("prompting_action")->nullable();
        });
        Schema::table('trade_collateral_baskets_archive', function (Blueprint $table) {
            $table->renameColumn("trade_collateral_baskets_id","trade_basket_type_id")->change();
            $table->string("currency");
            $table->enum("type",['tri','bi']);
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("user_id");
            $table->string("rating");
            $table->unsignedBigInteger("performed_by")->nullable();
            $table->string("prompting_action")->nullable();
        });
        Schema::table('c_t_trade_requests_archive', function (Blueprint $table) {
            $table->renameColumn("investiment_amount","investment_amount");
        });
    
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('c_t_trade_requests_archive', function (Blueprint $table) {
            $table->dropColumn("request_withdrawal_reason");
            $table->dropColumn("performed_by");
            $table->dropColumn("prompting_action");
        });
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            $table->dropColumn("rate_operator");
            $table->dropColumn("performed_by");
            $table->dropColumn("prompting_action");
        });
        Schema::table('c_t_trade_request_offer_deposits_archive', function (Blueprint $table) {
            $table->dropColumn("active_trade_event");
            $table->dropColumn("active_trade_events_batch_number");
            $table->dropColumn("performed_by");
            $table->dropColumn("prompting_action");
        });
        Schema::table('trade_basket_types_archive', function (Blueprint $table) {
            $table->renameColumn(' basket_type_id', 'baket_type_id');
            $table->dropColumn("performed_by");
            $table->dropColumn("prompting_action");
        });
        Schema::table('trade_collateral_baskets_archive', function (Blueprint $table) {
            $table->renameColumn("trade_basket_type_id","trade_collateral_baskets_id")->change();
            $table->dropColumn("currency");
            $table->dropColumn("type");
            $table->dropColumn("organization_id");
            $table->dropColumn("user_id");
            $table->dropColumn("rating");
            $table->dropColumn("performed_by");
            $table->dropColumn("prompting_action");
        });
        Schema::table('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            $table->rename("active_trade_event","active_trate_event")->nullable();
        });

        Schema::table('c_t_trade_requests_archive', function (Blueprint $table) {
            $table->renameColumn("investment_amount","investiment_amount");
        });

    }
}
