<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('seo_title')->nullable();
            $table->string('description')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('tags')->nullable();
            $table->dropColumn('slug');
            $table->dropColumn('meta_tag');
            $table->dropColumn('keywords');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('seo_title');
            $table->dropColumn('seo_description');
            $table->dropColumn('description');
            $table->dropColumn('tags');
            $table->text('meta_tag')->nullable();
            $table->text('keywords')->nullable();
            $table->string('slug');
        });
    }
}
