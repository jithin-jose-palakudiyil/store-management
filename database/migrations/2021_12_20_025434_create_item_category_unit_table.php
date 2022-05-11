<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCategoryUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_category_unit', function (Blueprint $table) {
            $table->id();
            $table->foreign('category_id')->references('id')->on('item_category');
            $table->integer('category_id')->unsigned();
            $table->foreign('measurement_id')->references('id')->on('measurements');
            $table->integer('measurement_id')->unsigned();
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
        Schema::dropIfExists('item_category_unit');
    }
}
