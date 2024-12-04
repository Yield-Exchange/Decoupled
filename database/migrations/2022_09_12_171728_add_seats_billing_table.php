<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatsBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats_billing', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no');
            $table->integer('seats');
            $table->decimal('rate');
            $table->decimal('total_amount');
            $table->boolean('is_paid')->default(false);
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('seats_billing');
    }
}
