<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotStoreItemsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_store_items', function (Blueprint $table) {
            $table->increments('id'); 
            $table->foreign('store_item_id')->references('id')->on('store_items_list');
            $table->integer('store_item_id')->unsigned();
            $table->foreign('batch_item_id')->references('id')->on('batch_items');
            $table->integer('batch_item_id')->unsigned();
            $table->foreign('pivot_indent_id')->references('id')->on('pivot_indent');
            $table->integer('pivot_indent_id')->unsigned();
            $table->text('location')->nullable(true); 
//            $table->tinyInteger('is_requested')->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('is_transferred')->default(0)->comment('0-pending, 1-transferred, 2-not transferred');
            $table->tinyInteger('is_recived')->default(0)->comment('0-pending, 1-received, 2-not received');
            $table->tinyInteger('is_breakage')->default(0)->comment('0-No, 1-Yes');
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
        Schema::dropIfExists('pivot_store_items');
    }
}
