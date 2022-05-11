<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{ 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username')->unique(); 
            $table->string('password');
            $table->string('role')->comment('master, store')->nullable(false);
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->integer('store_id')->nullable(true)->unsigned(); 
            $table->rememberToken();
            $table->tinyInteger('is_developer')->default(0)->comment('1-Yes,0-No');
            $table->tinyInteger('status')->default(1)->comment('1-Active,2-Inactive');
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
        Schema::dropIfExists('users');
    }
}
