<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('slug')->unique();
            $table->string('image');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->text('script')->nullable();
            $table->text('template')->nullable();
            $table->integer('category_id');
            $table->integer('test_type_id');
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
        Schema::dropIfExists('tests');
    }
}
