<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToCTTradeRequestCGOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_t_trade_request_c_g_offers', function (Blueprint $table) {
            $table->string("rate_type")->default("fixed");
            $table->double('variable_rate_value', 15, 2)->default(0.00);
            $table->double('fixed_rate', 15, 2)->default(0.00);
            $table->string("product_disclosure_statement")->nullable();
            $table->string("product_disclosure_url")->nullable();            
        });
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            $table->string("rate_type")->default("fixed");
            $table->double('variable_rate_value', 15, 2)->default(0.00);
            $table->double('fixed_rate', 15, 2)->default(0.00);
            $table->string("product_disclosure_statement")->nullable();
            $table->string("product_disclosure_url")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_t_trade_request_c_g_offers', function (Blueprint $table) {
           $table->dropColumn("rate_type");
           $table->dropColumn("variable_rate_value");
           $table->dropColumn("fixed_rate");
           $table->dropColumn("product_disclosure_statement");
           $table->dropColumn("product_disclosure_url");
           
        });
        Schema::table('c_t_trade_request_c_g_offers_archive', function (Blueprint $table) {
            $table->dropColumn("rate_type");
            $table->dropColumn("variable_rate_value");
            $table->dropColumn("fixed_rate");
            $table->dropColumn("product_disclosure_statement");
            $table->dropColumn("product_disclosure_url");
         });        
    }
}
