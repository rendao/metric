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
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('test_type_id');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('slug')->unique();
            $table->text('image')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->text('compute_api')->nullable();
            $table->boolean('compute_api_enabled')->default(0);
            $table->integer('template_id')->nullable();
            $table->boolean('template_enabled')->default(0);
            $table->boolean('is_active')->default(1);
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
