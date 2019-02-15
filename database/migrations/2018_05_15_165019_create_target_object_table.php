<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_object', function (Blueprint $table) {
            $table->increments('id');
            $table->string('old_id',24)->nullable();
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('appuser');
            $table->integer('network_id');
            $table->foreign('network_id')->references('id')->on('network');
            $table->string('made_by',500)->nullable();
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
        Schema::dropIfExists('target_object');
    }
}
