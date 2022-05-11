<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseEntryBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_entry_batch', function (Blueprint $table) {
            $table->increments('id'); 
            $table->foreign('purchase_entry_id')->references('id')->on('purchase_entry')->onDelete('cascade');
            $table->integer('purchase_entry_id')->unsigned(); 
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->integer('item_id')->unsigned(); 
            $table->tinyInteger('generate_id')->default(2)->comment('1-Yes ,2-No');
            
            $table->date('expiry_date')->nullable(true); 
            $table->string('amount');
            $table->string('quantity');
            $table->string('make_model')->nullable(true); 
            
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
        Schema::dropIfExists('purchase_entry_batch');
    }
}
