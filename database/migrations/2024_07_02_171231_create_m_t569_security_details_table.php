<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMT569SecurityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_t569_security_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_t569_general_information_id');
            $table->string('isin')->nullable()->comment('International Securities Identification Number(35B::ISIN)');
            $table->string('xs')->nullable()->comment('XS Identifier(XS)');
            $table->string('security_description')->nullable()->comment('Security Description(CAD)');
            $table->decimal('face_amount', 20, 2)->nullable()->comment('Face Amount(36B::SECV)');
            $table->string('safekeeping_account')->nullable()->comment('Safekeeping Account(97A::SAFE)');
            $table->string('denomination_currency')->nullable()->comment('Denomination Currency(11A::DENO)');
            $table->string('market_price')->nullable()->comment('Market Price(90A::MRKT)');
            $table->string('rating_source')->nullable()->comment('Rating Source(94B::RATS)');
            $table->string('rating_value')->nullable()->comment('Rating Value(70C::RATS)');
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
        Schema::dropIfExists('m_t569_security_details');
    }
}
