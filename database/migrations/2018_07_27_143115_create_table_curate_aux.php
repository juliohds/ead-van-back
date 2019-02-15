<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCurateAux extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curate_aux', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('main_object_id')->nullable();
            $table->foreign('main_object_id')->references('id')->on('main_object');
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('appuser');
            $table->timestamps();
            $table->softDeletes();
        
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curate_aux');
    }
}
