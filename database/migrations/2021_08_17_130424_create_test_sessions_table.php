<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('code')->unique();
            $table->unsignedBigInteger('test_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('current_question_id')->default(0);
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->dateTime('completed_at')->nullable();
            $table->integer('total_time_taken')->default(0);
            $table->string('status')->default('start')->index();
            $table->json('result')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_sessions');
    }
}
