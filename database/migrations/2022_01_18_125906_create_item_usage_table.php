<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemUsageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_usage', function (Blueprint $table) {
            $table->increments('id'); 
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->integer('item_id')->unsigned();
            $table->string('usage_date'); 
            $table->string('usage_quantity');
            $table->foreign('store_id')->references('id')->on('store');
            $table->integer('store_id')->nullable(true)->unsigned();
            
            $table->foreign('batch_item_id')->references('id')->on('batch_items');
            $table->integer('batch_item_id')->nullable(true)->unsigned();
            
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
        Schema::dropIfExists('item_usage');
    }
}
