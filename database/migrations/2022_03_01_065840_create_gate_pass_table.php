<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatePassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gate_pass', function (Blueprint $table) {
            $table->increments('id');
            $table->foreign('breakage_id')->references('id')->on('breakage')   ;
            $table->integer('breakage_id')->unsigned();
            $table->foreign('pivot_store_id')->references('id')->on('pivot_store_items')   ;
            $table->integer('pivot_store_id')->nullable(true)->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers')   ;
            $table->integer('supplier_id')->unsigned();
            $table->string('pass_date',225);
            $table->string('name',225);
            $table->string('contact_number',225);
            $table->string('email',225);
            $table->text('purpose')->nullable(true); 
            $table->text('comments')->nullable(true); 
            $table->tinyInteger('status')->default(0)->comment('0-open, 1-closed');
            $table->tinyInteger('is_breakage')->default(0)->comment('0-open, 1-closed, 2-not closed');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->softDeletesTz();
        });
        $statement = "ALTER TABLE items AUTO_INCREMENT = 1000;";
        \DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gate_pass');
    }
}
