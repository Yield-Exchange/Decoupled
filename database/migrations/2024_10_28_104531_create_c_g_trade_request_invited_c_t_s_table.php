<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCGTradeRequestInvitedCTSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_g_trade_request_invited_c_t_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("c_g_trade_request_id");
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("invited_user_id")->nullable();
            $table->dateTime('invitation_date');
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable(); 
            $table->enum('invitation_status',['INVITED','UNINVITED','PARTICIPATED','DID_NOT_PARTICIPATE','ON_REVIEW'])->default("INVITED");
            $table->enum('is_test',[1,0])->default(0);  
            $table->enum('seen',['Yes','No'])->default('No'); 
            $table->timestamps();
        });
        Schema::create('c_g_trade_request_invited_c_t_archive', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger("c_g_trade_request_invited_c_t_id");
            $table->unsignedBigInteger("c_g_trade_request_id");
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("invited_user_id")->nullable();
            $table->dateTime('invitation_date');
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable(); 
            $table->enum('invitation_status',['INVITED','UNINVITED','PARTICIPATED','DID_NOT_PARTICIPATE','ON_REVIEW'])->default("INVITED");;
            $table->enum('is_test',[1,0])->default(0);  
            $table->enum('seen',['Yes','No'])->default('No'); 
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
        Schema::dropIfExists('c_g_trae_request_invited_c_t_s');
        Schema::dropIfExists('c_g_trade_request_invited_c_t_archive');
    }
}
