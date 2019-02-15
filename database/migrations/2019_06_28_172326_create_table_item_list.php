<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableItemList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_list', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('user_list_id');
            $table->foreign('user_list_id')->references('id')->on('user_list');
            $table->integer('target_object_id')->nullable();
            $table->foreign('target_object_id')->references('id')->on('target_object');
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
        Schema::dropIfExists('item_list');
    }
}
