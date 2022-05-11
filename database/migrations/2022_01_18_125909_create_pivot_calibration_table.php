<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotCalibrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_calibration', function (Blueprint $table) {
            $table->increments('id'); 
            $table->foreign('calibration_id')->references('id')->on('calibration')->onDelete('cascade');
            $table->integer('calibration_id')->unsigned(); 
            $table->date('date'); 
            $table->date('completion_date')->nullable(true); 
            $table->text('comments')->nullable(true); 
            $table->tinyInteger('status')->default(0)->comment('0-initialized,1-completed,2-hold,3-rejected');
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
        Schema::dropIfExists('pivot_calibration');
    }
}
