<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('product_id');
            $table->integer('user_id');
            $table->integer('variation')->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('info')->nullable();
            $table->enum('preferred_method', ['EMAIL', 'SMS'])->default('EMAIL');

            $table->softDeletes();

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
        Schema::dropIfExists('alerts');
    }
}
