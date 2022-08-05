<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('project_number')->nullable();
            $table->string('correspondence_no')->nullable();
            $table->string('datetime')->nullable();
            $table->string('subject')->nullable();
            $table->json('response')->nullable();
            $table->json('attachment')->nullable();
            $table->json('tag')->nullable();
            $table->string('location')->nullable();
            $table->longText('memo')->nullable();
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memos');
    }
}
