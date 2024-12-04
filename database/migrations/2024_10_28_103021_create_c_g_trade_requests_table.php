<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCGTradeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_g_trade_requests', function (Blueprint $table) {
            $table->id();
            $table->enum("source",['New','Copied','AI'])->default("New");
            $table->unsignedBigInteger("trade_product_id");
            $table->unsignedBigInteger("copied_from_id")->nullable();
            $table->string("reference_no")->nullable();  
            $table->string("bulk_reference_no")->nullable(); 
            $table->string("currency")->nullable();
            $table->enum('request_status',['WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','NO_OFFERS_RECEIVED','ON_REVIEW'])->default("ACTIVE");       
            $table->enum('is_test',[1,0])->default(0);   
            $table->enum('is_demo',[1,0])->default(0);  
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable();  
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger('admin_loggedin_as');
            $table->timestamps();
        });
        Schema::create('c_g_trade_requests_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("c_g_trade_request_id");
            $table->enum("source",['New','Copied','AI'])->default("New");
            $table->unsignedBigInteger("copied_from_id")->nullable();
            $table->unsignedBigInteger("trade_product_id");
            $table->string("bulk_reference_no")->nullable();  
            $table->string("currency");
            $table->enum('request_status',['WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','NO_OFFERS_RECEIVED','ON_REVIEW'])->default("ACTIVE");
              $table->enum('is_test',[1,0])->default(0);   
            $table->enum('is_demo',[1,0])->default(0);  
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable();  
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger('admin_loggedin_as');
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
        Schema::dropIfExists('c_g_trade_requests');
        Schema::dropIfExists('c_g_trade_requests_archive');
    }
}
