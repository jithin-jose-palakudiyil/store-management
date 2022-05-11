<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); 
//            $table->foreign('measurement_id')->references('id')->on('measurements');
//            $table->integer('measurement_id')->unsigned();
            $table->string('slug')->unique()->nullable(false);
            $table->tinyInteger('allow_usage')->default(2)->comment('1-Yes,2-No');
            $table->tinyInteger('status')->default(1)->comment('1-Active,2-Inactive');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_category');
    }
}
