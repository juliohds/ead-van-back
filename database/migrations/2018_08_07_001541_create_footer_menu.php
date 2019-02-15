<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFooterMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footer_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url',1000);
            $table->string('title',255);
            $table->boolean('highlighted');
            $table->smallInteger('ui_index');
            $table->smallInteger('column');
            $table->integer('network_config_id');
            $table->foreign('network_config_id')->references('id')->on('network_config');
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
        Schema::dropIfExists('footer_menu');
    }
}
