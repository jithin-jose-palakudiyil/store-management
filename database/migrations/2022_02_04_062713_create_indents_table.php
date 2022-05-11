<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indents', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('date');
            $table->foreign('request_from')->references('id')->on('store');
            $table->integer('request_from')->nullable(true)->unsigned(); 
            $table->foreign('request_to')->references('id')->on('store');
            $table->integer('request_to')->nullable(true)->unsigned();
            $table->tinyInteger('status')->default(0)->comment('0-sotre1 requested, 1-authority processed, 2- sotre1 request processed by sotre 2, 3-sotre1 processed sotre2 comments (completed the process) ');
            $table->tinyInteger('authority_status')->default(0)->comment('0-pending, 1-processed');
            $table->tinyInteger('to_status')->default(0)->comment('0-pending, 1-processed ');
            $table->tinyInteger('from_status')->default(0)->comment('0-pending, 1-processed ');
            
            $table->tinyInteger('from_warehouse')->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('to_warehouse')->default(0)->comment('0-No, 1-Yes');
            $table->text('comments')->nullable(true);
            
           
            $table->foreign('requested_user')->references('id')->on('users');
            $table->integer('requested_user')->unsigned();
            
            $table->foreign('authority_user')->references('id')->on('users');
            $table->integer('authority_user')->nullable(true)->unsigned();
            
            $table->foreign('to_user')->references('id')->on('users');
            $table->integer('to_user')->nullable(true)->unsigned();
            
            $table->foreign('completed_from_user')->references('id')->on('users');
            $table->integer('completed_from_user')->nullable(true)->unsigned();
            
            
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
        Schema::dropIfExists('indents');
    }
}
