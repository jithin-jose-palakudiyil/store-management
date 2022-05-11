<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreItemsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_items_list', function (Blueprint $table) {
            $table->increments('id'); 
            $table->text('quantity')->nullable(false)->default(0); 
            $table->foreign('item_id')->references('id')->on('items')   ;
            $table->integer('item_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('store');
            $table->integer('store_id')->unsigned();
//            $table->foreign('batch_item_id')->references('id')->on('batch_items');
//            $table->integer('batch_item_id')->nullable(true)->unsigned();
            $table->text('location')->nullable(true); 
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
        Schema::dropIfExists('store_items_list');
    }
}
