<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('reference_id')->index();


            $table->string('name');
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('class')->nullable();
            $table->string('category_name')->nullable();
            $table->string('status')->nullable();
            $table->string('discount')->nullable();

            $table->integer('quantity')
                ->nullable()
                ->default(0);

            $table->decimal('discovery')->nullable();
            $table->decimal('ebucks')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('retail_price', 10, 2)->nullable();

            $table->json('data');

            $table->unique(['reference_id', 'class']);

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
        Schema::dropIfExists('products');
    }
}
