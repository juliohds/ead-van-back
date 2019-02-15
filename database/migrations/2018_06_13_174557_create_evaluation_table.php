<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pedagogical');
            $table->integer('content');
            $table->integer('technical');
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
        Schema::dropIfExists('evaluation');
    }
}
