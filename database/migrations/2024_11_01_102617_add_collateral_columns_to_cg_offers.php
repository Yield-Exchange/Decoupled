<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollateralColumnsToCgOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_g_trade_request_invited_c_t_offers', function (Blueprint $table) {
            //
           $table->integer("trade_organization_collateral_c_u_s_i_p_s_id")->nullable();  
           $table->integer("trade_tri_basket_third_party_id")->nullable();  
           $table->string("trade_collateral_basket_id")->nullable()->change();
           $table->string("rate_type")->default("fixed");
           $table->double('variable_rate_value', 15, 2)->default(0.00);
           $table->double('fixed_rate', 15, 2)->default(0.00);
           $table->string("product_disclosure_statement")->nullable();
           $table->string("product_disclosure_url")->nullable(); 
           $table->enum('rate_operator',['+','-'])->nullable();
           $table->string('currency');
        });
        Schema::table('c_g_trade_request_invited_c_t_offers_archive', function (Blueprint $table) {
           $table->integer("trade_organization_collateral_c_u_s_i_p_s_id")->nullable();  
           $table->integer("trade_tri_basket_third_party_id")->nullable();  
           $table->string("trade_collateral_basket_id")->nullable()->change();
           $table->string("rate_type")->default("fixed");
           $table->double('variable_rate_value', 15, 2)->default(0.00);
           $table->double('fixed_rate', 15, 2)->default(0.00);
           $table->string("product_disclosure_statement")->nullable();
           $table->string("product_disclosure_url")->nullable();
           $table->enum('rate_operator',['+','-'])->nullable();
           $table->string('currency');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_g_trade_request_invited_c_t_offers', function (Blueprint $table) {
            $table->dropColumn("trade_organization_collateral_c_u_s_i_p_s_id"); 
            $table->dropColumn("trade_tri_basket_third_party_id");
            $table->dropColumn("trade_collateral_basket_id");
            $table->dropColumn("rate_type");
            $table->dropColumn('variable_rate_value');
            $table->dropColumn('fixed_rate');
            $table->dropColumn("product_disclosure_statement");
            $table->dropColumn("product_disclosure_url");
            $table->dropColumn('rate_operator');
            $table->dropColumn('currency');
        });
        Schema::table('c_g_trade_request_invited_c_t_offers_archive', function (Blueprint $table) {
            $table->dropColumn("trade_organization_collateral_c_u_s_i_p_s_id"); 
            $table->dropColumn("trade_tri_basket_third_party_id");
            $table->dropColumn("trade_collateral_basket_id");
            $table->dropColumn("rate_type");
            $table->dropColumn('variable_rate_value');
            $table->dropColumn('fixed_rate');
            $table->dropColumn("product_disclosure_statement");
            $table->dropColumn("product_disclosure_url");
            $table->dropColumn('rate_operator');
            $table->dropColumn('currency');
        });
    }
}
