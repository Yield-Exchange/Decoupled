<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUploadNameToOrganizationDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_documents', function (Blueprint $table) {
            //
            $table->text("user_uploaded_file_name")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_documents', function (Blueprint $table) {
            //
            $table->dropColumn("user_uploaded_file_name");
        });
    }
}
