<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breakage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('what_is',225)->nullable(true);
            
            $table->foreign('item_id')->references('id')->on('items')   ;
            $table->integer('item_id')->nullable(true)->unsigned();
            
            $table->foreign('pivot_store_item_id')->references('id')->on('pivot_store_items')   ;
            $table->integer('pivot_store_item_id')->nullable(true)->unsigned();
            
            $table->foreign('store_id')->references('id')->on('store');
            $table->integer('store_id')->nullable(true)->unsigned();
            
            $table->foreign('batch_item_id')->references('id')->on('batch_items');
            $table->integer('batch_item_id')->nullable(true)->unsigned();
            
            $table->tinyInteger('is_responsible')->nullable(false)->comment('0-student, 1-lab incharge');
            $table->tinyInteger('is_permanently')->nullable(false)->default(0)->comment('0-not processed, 1-approved , 2-rejected');
            $table->tinyInteger('is_status')->nullable(false)->default(0)->comment('0-open, 1-closed');
            $table->string('breakage_date',225);
            $table->string('price',225)->nullable(true);
            $table->text('comments')->nullable(true);
            $table->tinyInteger('status')->default(0)->comment('0-waiting for payment, 1-collect payment, 2-replace item , 2-maintenance item ');
            $table->tinyInteger('step')->default(0)->comment('0-waiting for processing authority, 1-authority processed, 2-close , 3-rejected , 4-permanently damaged ');
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
        Schema::dropIfExists('breakage');
    }
}
