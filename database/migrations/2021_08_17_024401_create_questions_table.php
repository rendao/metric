<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('question_type_id');
            $table->unsignedBigInteger('test_id')->index();
            $table->integer('position')->nullable();
            $table->string('trait')->nullable();
            $table->text('question')->nullable();
            $table->json('options')->nullable();
            $table->text('image')->nullable();
            $table->text('hint')->nullable();
            $table->boolean('skippable')->default(1);
            $table->boolean('finish')->default(0);
            $table->timestamps();

            // $table->foreign('test_id')
            //     ->references('id')->on('tests')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
