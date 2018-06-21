<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('reference_id');
            $table->integer('department_id')->nullable();
            $table->integer('category_id')->nullable();

            $table->string('class');
            $table->string('resolved_id');

            $table->string('parent_path')->nullable();
            $table->string('path')->nullable();
            $table->string('slug')->nullable();
            $table->string('title')->nullable();
            $table->string('url')->nullable();

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
        Schema::dropIfExists('categories');
    }
}
