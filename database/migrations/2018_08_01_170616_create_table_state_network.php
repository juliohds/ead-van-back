<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStateNetwork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_network', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('state_id');
            $table->foreign('state_id')->references('id')->on('state');
            $table->integer('network_id');
            $table->foreign('network_id')->references('id')->on('network');
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
        Schema::dropIfExists('state_network');
    }
}
