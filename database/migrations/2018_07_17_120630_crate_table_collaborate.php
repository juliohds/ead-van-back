<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateTableCollaborate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborate', function (BluePrint $table) {

            $table->increments('id');
            $table->integer('network_user_id');
            $table->foreign('network_user_id')->references('id')->on('network_user');
            $table->integer('target_object_id');
            $table->foreign('target_object_id')->references('id')->on('target_object');
            $table->string('status')->default("New");
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
        Schema::dropIfExists('collaborate');
    }
}
