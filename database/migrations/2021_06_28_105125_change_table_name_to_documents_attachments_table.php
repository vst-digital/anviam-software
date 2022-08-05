<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableNameToDocumentsAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('documents_attachments', 'documents_threads');
        Schema::table('documents_threads', function (Blueprint $table) {
            $table->integer('project_id')->nullable()->after('uploaded_by');
            $table->json('users')->nullable()->after('uploaded_by');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents_threads', function (Blueprint $table) {
            //
        });
    }
}
