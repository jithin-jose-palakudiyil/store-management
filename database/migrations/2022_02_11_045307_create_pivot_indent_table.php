<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotIndentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_indent', function (Blueprint $table) {
            $table->increments('id');
            
            $table->foreign('indent_id')->references('id')->on('indents');
            $table->integer('indent_id')->unsigned();
            
            $table->string('requested_quantity');
            $table->string('approved_quantity')->nullable(true);
            $table->foreign('category_id')->references('id')->on('item_category');
            $table->integer('category_id')->unsigned();
            
            $table->foreign('item_id')->references('id')->on('items');
            $table->integer('item_id')->unsigned();
            
//            $table->foreign('batch_item_id')->references('id')->on('batch_items');
//            $table->integer('batch_item_id')->nullable(true)->unsigned();
            
//            $table->foreign('requested_user')->references('id')->on('users');
//            $table->integer('requested_user')->unsigned();
//            
//            $table->foreign('authority_user')->references('id')->on('users');
//            $table->integer('authority_user')->nullable(true)->unsigned();
//            
//            $table->foreign('transferred_user')->references('id')->on('users');
//            $table->integer('transferred_user')->nullable(true)->unsigned();
//            
            $table->tinyInteger('is_transferred')->default(0)->comment('0-pending, 1-transferred, 2-not transferred');
            $table->string('transferred_qty')->default(0);
            $table->string('recived_qty')->default(0);
            
            $table->tinyInteger('is_recived')->default(0)->comment('0-pending, 1-transferred, 2-not transferred');
            $table->tinyInteger('status')->default(0)->comment('0-pending, 1-approved, 2-Rejected');
            
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
        Schema::dropIfExists('pivot_indent');
    }
}
