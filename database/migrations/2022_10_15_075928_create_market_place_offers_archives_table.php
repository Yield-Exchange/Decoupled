<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketPlaceOffersArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_place_offers_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('market_place_offer_id');
            $table->boolean("is_featured")->default(false);
            $table->string('reference_no');
            $table->enum('term_length_type',['DAYS', 'MONTHS', 'HISA']);
            $table->decimal('term_length');
            $table->unsignedBigInteger('product_id');
            $table->integer('lockout_period');
            $table->decimal('minimum_amount');
            $table->decimal('maximum_amount');
            $table->string('compound_frequency');
            $table->decimal('interest_paid');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('modified_by');
            $table->string('modified_section');
            $table->boolean('is_test')->default(false);
            $table->enum('status',['ACTIVE','INACTIVE','EXPIRED']);
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
        Schema::dropIfExists('market_place_offers_archives');
    }
}
