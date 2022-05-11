<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotBreakageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_breakage', function (Blueprint $table) {
            $table->increments('id');
            $table->foreign('breakage_id')->references('id')->on('breakage')   ;
            $table->integer('breakage_id')->unsigned();
            $table->string('student_id',225)->nullable(true);
            $table->string('employee_id',225)->nullable(true);
            $table->string('name',225)->nullable(true);
            $table->string('contact_number',225)->nullable(true);
            $table->string('batch',225)->nullable(true);
            $table->string('class',225)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_breakage');
    }
}
