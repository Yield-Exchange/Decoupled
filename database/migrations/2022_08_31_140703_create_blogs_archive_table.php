<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('modified_date')->nullable();
            $table->string('modified_by')->nullable();
        });

        Schema::create('blogs_archive', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->enum('status',['DRAFT','PUBLISHED','DELETED','PENDING']);
            $table->text('body');
            $table->string('main_image')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('modified_section')->nullable();
            $table->dateTime('modified_date')->nullable();
            $table->string('modified_by')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('description')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('tags')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs_archive');

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('modified_date');
            $table->dropColumn('modified_by');
        });
    }
}
