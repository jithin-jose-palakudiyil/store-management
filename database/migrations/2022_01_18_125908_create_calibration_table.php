<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalibrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calibration', function (Blueprint $table) {
            $table->increments('id'); 
            $table->foreign('batch_item_id')->references('id')->on('batch_items')->onDelete('cascade');
            $table->integer('batch_item_id')->unsigned();
            $table->foreign('calibration_type_id')->references('id')->on('calibration_type');
            $table->integer('calibration_type_id')->unsigned();
//            $table->string('date'); 
//            $table->string('last_calibration_date'); 
            $table->string('calibration_by'); 
            $table->string('contact_number');
            $table->string('contact_email'); 
            $table->tinyInteger('status')->default(0)->comment('1-Active,2-Inactive');
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
        Schema::dropIfExists('calibration');
    }
}
