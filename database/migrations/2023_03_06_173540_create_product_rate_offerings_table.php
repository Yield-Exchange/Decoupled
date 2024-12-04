<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRateOfferingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('public_offers');
        Schema::dropIfExists('public_offers_terms');

        Schema::create('public_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('term_length');
            $table->string('term_length_type');
            $table->decimal('minimum_deposit');
            $table->decimal('interest_rate');
            $table->enum('status',['ACTIVE','INACTIVE']);
            $table->timestamps();
        });

        Schema::create('public_organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('public_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_type');
            $table->integer('redemption_period');
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
        Schema::dropIfExists('public_rates');
        Schema::dropIfExists('public_organizations');
        Schema::dropIfExists('public_products');
    }
}