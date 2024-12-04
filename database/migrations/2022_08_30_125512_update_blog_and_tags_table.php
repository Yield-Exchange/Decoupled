<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBlogAndTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_and_tags', function (Blueprint $table) {
            $table->enum('status', ['DRAFT','PUBLISHED','NOT_PUBLISHED','DELETED'])->default('PUBLISHED')->after('blog_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_and_tags', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
