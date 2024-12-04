<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFICampaignProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_i_campaign_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_type_id");
            $table->enum("term_length_type",["DAYS","MONTHS"])->default("DAYS");
            $table->integer("term_length");
            $table->integer("lockout_period")->nullable();
            $table->enum("compound_frequency",["AT MATURITY","ANNUALLY","SEMI-ANNUALLY","QUARTERLY","MONTHLY","DAILY"])->default("AT MATURITY");
            
            $table->enum("interest_paid",["AT MATURITY","ANNUALLY","SEMI-ANNUALLY","QUARTERLY","MONTHLY","DAILY"])->default("AT MATURITY");
            $table->string("default_product_name",255);
            $table->string("pds",255)->nullable();
            $table->integer("fi_id");
            $table->unsignedBigInteger("created_by");
            $table->timestamps();
    
        });
        Schema::create('f_i_campaign_products_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_type_id");
            $table->enum("term_length_type",["DAYS","MONTHS"])->default("DAYS");
            $table->integer("term_length");
            $table->integer("lockout_period")->nullable();
            $table->enum("compound_frequency",["AT MATURITY","ANNUALLY","SEMI-ANNUALLY","QUARTERLY","MONTHLY","DAILY"])->default("AT MATURITY");
            
            $table->enum("interest_paid",["AT MATURITY","ANNUALLY","SEMI-ANNUALLY","QUARTERLY","MONTHLY","DAILY"])->default("AT MATURITY");
            $table->string("default_product_name",255);
            $table->string("pds",255)->nullable();
            $table->integer("fi_id");
            $table->unsignedBigInteger("created_by");
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
        Schema::dropIfExists('f_i_campaign_products');
        Schema::dropIfExists('f_i_campaign_products_archives');
    }
}
