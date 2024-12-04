<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCTTradeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_t_trade_requests', function (Blueprint $table) {
            $table->id();
            $table->double("investiment_amount");            
            $table->string("reference_no");
            $table->string("bulk_reference_no")->nullable();            
            $table->enum("term_length_type",['DAYS','MONTHS']);
            $table->integer('term_length');
            $table->dateTime("trade_time");
            $table->string("currency");
            $table->enum('request_status',['WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','NO_OFFERS_RECEIVED','ON_REVIEW']);
            $table->dateTime('closed_date')->nullable();
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger('admin_loggedin_as');
            $table->enum('is_test',[1,0])->default(0);   
            $table->enum('is_demo',[1,0])->default(0);  
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable();    
            $table->string('special_instructions')->nullable();   
            $table->timestamps();

            $table->index('reference_no');
            $table->index('organization_id');
            $table->index('user_id');

        });
        Schema::create('c_t_trade_requests_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("c_r_trade_requests_id");
            $table->double("investiment_amount");            
            $table->string("reference_no");
            $table->string("bulk_reference_no")->nullable();            
            $table->enum("term_length_type",['DAYS','MONTHS','HISA']);
            $table->integer('term_length');
            $table->dateTime("trade_time");
            $table->string("currency");
            $table->enum('request_status',['WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','NO_OFFERS_RECEIVED','ON_REVIEW']);
            $table->dateTime('closed_date')->nullable();
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger('admin_loggedin_as');
            $table->enum('is_test',[1,0])->default(0);   
            $table->enum('is_demo',[1,0])->default(0);  
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable();    
            $table->string('special_instructions')->nullable();   
            $table->timestamps();

            $table->index('reference_no');
            $table->index('organization_id');
            $table->index('user_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c_t_trade_requests');
        Schema::dropIfExists('c_t_trade_requests_archive');
        
    }
}
