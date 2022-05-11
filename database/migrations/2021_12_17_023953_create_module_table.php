<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',225)->nullable(false); 
            $table->string('slug',225)->nullable(false); 
            $table->tinyInteger('is_master')->nullable(false)->default('0')->comment('1-Yes, 0-No'); 
            $table->tinyInteger('is_store')->nullable(false)->default('0')->comment('1-Yes, 0-No'); 
            $table->tinyInteger('status')->default('1')->comment('1-Yes, 2-No')->nullable(false);
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
        Schema::dropIfExists('module');
    }
}
