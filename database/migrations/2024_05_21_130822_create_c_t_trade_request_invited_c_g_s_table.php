<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCTTradeRequestInvitedCGSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_t_trade_request_invited_c_g_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("invited_user_id");
            $table->dateTime('invitation_date');
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable(); 
            $table->enum('invitation_status',['INVITED','UNINVITED','PARTICIPATED','DID_NOT_PARTICIPATE','ON_REVIEW']);
            $table->enum('is_test',[1,0])->default(0);  
            $table->enum('seen',['Yes','No'])->default('No'); 
            $table->timestamps();
        });
        Schema::create('c_t_trade_request_invited_c_g_s_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("c_r_trade_request_invited_c_t_s_id");
            $table->unsignedBigInteger("organization_id");
            $table->unsignedBigInteger("invited_user_id");
            $table->dateTime('invitation_date');
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable();  
            $table->enum('invitation_status',['INVITED','UNINVITED','PARTICIPATED','DID_NOT_PARTICIPATE','ON_REVIEW']);
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
        Schema::dropIfExists('c_t_trade_request_invited_c_g_s');
        Schema::dropIfExists('c_t_trade_request_invited_c_g_s_archive');
        
    }
}
