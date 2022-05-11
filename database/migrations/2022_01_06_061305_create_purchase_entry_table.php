<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_entry', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_id'); 
            $table->date('invoice_date');
            $table->string('total_amount');
            $table->string('entry_date');
            $table->string('invoice_file')->nullable(true);
            $table->string('purchase_entry_file')->nullable(true);
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->integer('supplier_id')->nullable()->unsigned(); 
            $table->tinyInteger('status')->default(1)->comment('1-Active,2-Inactive');
            $table->tinyInteger('step')->default(1)->comment('1-first step ,2-second step');
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
        Schema::dropIfExists('purchase_entry');
    }
}
