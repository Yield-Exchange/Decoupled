<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCTTradeRequestOfferChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_t_trade_request_offer_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sent_by");
            $table->unsignedBigInteger("sent_to");
            $table->text("message")->nullable();
            $table->unsignedBigInteger("c_t_trade_request_offer_id");
            $table->enum("status",["NEW","SEEN"])->default("NEW");
            $table->unsignedBigInteger("sent_by_organization_id");
            $table->unsignedBigInteger("sent_to_organization_id");    
            $table->boolean("is_test")->default(1);      
            $table->text("file")->nullable();  
            $table->dateTime("seen_at")->nullable();
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
        Schema::dropIfExists('c_t_trade_request_offer_chats');
    }
}
