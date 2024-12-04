<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCTTradeRequestOfferDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_t_trade_request_offer_deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->string('deposit_reference_no');
            $table->double('offered_amount');
            $table->dateTime('trade_date')->nullable();
            $table->string('gic_number')->nullable();
            $table->dateTime('maturity_date')->nullable();
            $table->enum('deposit_status',['PENDING_DEPOSIT','INCOMPLETE','EARLY_REDEMPTION','ACTIVE','MATURED','WITHDRAWN']);
        
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable(); 
            $table->enum("admins_notified",[1,0])->default(0);
            $table->dateTime("admins_notified_date")->nullable();
            $table->unsignedBigInteger('created_by');
            $table->string("deposit_inactivate_reason")->nullable();
            $table->dateTime('redemption_date')->nullable();
            $table->timestamps();
        });

        Schema::create('c_t_trade_request_offer_deposits_archive', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('c_r_trade_request_offer_deposits_id');
            $table->unsignedBigInteger('offer_id');
            $table->string('deposit_reference_no');
            $table->double('offered_amount');
            $table->dateTime('trade_date')->nullable();
            $table->string('gic_number')->nullable();
            $table->dateTime('maturity_date')->nullable();
            $table->enum('deposit_status',['PENDING_DEPOSIT','INCOMPLETE','EARLY_REDEMPTION','ACTIVE','MATURED','WITHDRAWN']);
          
            $table->dateTime("modified_date")->nullable();
            $table->string("modified_section")->nullable(); 
            $table->enum("admins_notified",[1,0])->default(0);
            $table->dateTime("admins_notified_date")->nullable();
            $table->unsignedBigInteger('created_by');
            $table->string("deposit_inactivate_reason")->nullable();
            $table->dateTime('redemption_date')->nullable();
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
        Schema::dropIfExists('c_t_trade_request_offer_deposits');
        Schema::dropIfExists('c_t_trade_request_offer_deposits_archive');
        
    }
}
