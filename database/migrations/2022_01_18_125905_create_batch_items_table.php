<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_items', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('unique_id')->unique(); 
            $table->foreign('batch_id')->references('id')->on('purchase_entry_batch')->onDelete('cascade');
            $table->integer('batch_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->integer('item_id')->unsigned(); 
            $table->string('expiry_date')->nullable(true);
            $table->tinyInteger('whs_breakage')->default(0)->comment('0-No, 1-Yes');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->softDeletesTz();
        });
        
        $statement = "ALTER TABLE batch_items AUTO_INCREMENT = 1000;";
        \DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_items');
    }
}
