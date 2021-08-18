<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_scores', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('code')->unique();
            $table->unsignedBigInteger('test_id');
            $table->string('trait')->nullable();
            $table->integer('start')->nullable();
            $table->integer('end')->nullable();
            $table->string('name')->nullable();
            $table->text('image')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_scores');
    }
}
