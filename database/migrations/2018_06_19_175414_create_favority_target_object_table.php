<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavorityTargetObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favority_target_object', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('picked')->default(false);
            $table->integer('network_user_id');
            $table->foreign('network_user_id')->references('id')->on('network_user');
            $table->integer('target_object_id');
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
        Schema::dropIfExists('favority_target_object');
    }
}
