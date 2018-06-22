<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('products', function (Blueprint $table) {

            $table->string('slug')->nullable();
            $table->integer('old_selling_price')->nullable();
            $table->string('external_id')->index();
            $table->string('saving')->nullable();
            $table->boolean('is_on_special')->default(false);
            $table->integer('stock_on_hand')->nullable();
            $table->dateTime('date_released')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('products', function (Blueprint $table) {


            $table->dropColumn('external_id');
            $table->dropColumn('old_selling_price');
            $table->dropColumn('saving');
            $table->dropColumn('is_on_special');
            $table->dropColumn('stock_on_hand');
            $table->dropColumn('date_released');

        });
    }
}
