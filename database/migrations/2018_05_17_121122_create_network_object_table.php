<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_object', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_id');
            $table->foreign('network_id')->references('id')->on('network');
            $table->integer('main_object_id');
            $table->foreign('main_object_id')->references('id')->on('main_object');
            $table->integer('workflow_id');
            $table->foreign('workflow_id')->references('id')->on('workflow');
            $table->unique(['main_object_id','network_id']);
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
        Schema::dropIfExists('network_object');
    }
}
