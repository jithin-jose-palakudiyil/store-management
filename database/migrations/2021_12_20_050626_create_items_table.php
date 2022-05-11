<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id'); 
            $table->foreign('category_id')->references('id')->on('item_category')->onDelete('cascade');
            $table->integer('category_id')->unsigned();
            $table->foreign('measurement_id')->references('id')->on('measurements');
            $table->integer('measurement_id')->unsigned();
            $table->string('name'); 
            $table->text('location')->nullable(true); 
            $table->string('slug')->unique()->nullable(false);  
            $table->text('quantity')->nullable(false)->default(0); 
            $table->tinyInteger('status')->default(1)->comment('1-Active,2-Inactive');
            $table->tinyInteger('has_unique_id')->default(0)->comment('1-Yes,0-No');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->softDeletesTz();
        });
        $statement = "ALTER TABLE items AUTO_INCREMENT = 1000;";
        \DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
