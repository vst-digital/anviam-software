<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateMemoThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memo_threads', function (Blueprint $table) {
            $table->id();
            $table->integer('memo_id')->nullable();
            $table->longText('memo')->nullable();
            $table->integer('user_id')->nullable();
            $table->json('attachment')->nullable();
            $table->json('tag')->nullable();
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
        Schema::dropIfExists('memo_threads');
    }
}
