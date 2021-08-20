<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_sessions', function (Blueprint $table) {
            // $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_id');
            $table->unsignedBigInteger('test_session_id');
            $table->unsignedBigInteger('question_id');
            $table->string('trait')->nullable();
            $table->json('option')->nullable();
            $table->integer('duration')->default(0);
            $table->string('status')->default('unanswered');
            $table->boolean('skipped')->default(0);
            $table->timestamps();

            $table->primary(['test_session_id', 'test_id', 'question_id'], 'question_sessions_primary');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_sessions');
    }
}
