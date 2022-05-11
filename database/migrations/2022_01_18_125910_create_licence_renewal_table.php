<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicenceRenewalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licence_renewal', function (Blueprint $table) {
            $table->increments('id'); 
            $table->foreign('batch_item_id')->references('id')->on('batch_items')->onDelete('cascade');
            $table->integer('batch_item_id')->unsigned(); 
            $table->string('licence_no'); 
            $table->date('expiry_date'); 
            $table->date('renewed_date')->nullable(true); ; 
            $table->string('name');
            $table->string('contact_number');
            $table->string('contact_email'); 
            $table->text('comments')->nullable(true); 
            $table->tinyInteger('status')->default(0)->comment('0-processing,1-completed');
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
        Schema::dropIfExists('licence_renewal');
    }
}
