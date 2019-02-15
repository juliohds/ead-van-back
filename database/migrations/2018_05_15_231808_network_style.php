<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NetworkStyle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_style', function (Blueprint $table) {
            $table->increments('id');
            $table->string('background_color',7);
            $table->string('base_color',7);
            $table->string('bg_color',7);
            $table->string('color_theme',7);
            $table->string('custom_style',500);
            $table->string('darker_color',7);
            $table->string('highlight_image',500);
            $table->string('lighter_color',7);
            $table->string('logo',500);
            $table->string('tx_color',7);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('network', function (Blueprint $table) {
            $table->integer('network_style_id')->nullable();
            $table->foreign('network_style_id')->references('id')->on('network_style');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('network', function (Blueprint $table) {
            $table->DropForeign(['network_style_id']);
        });
        Schema::drop('network_style');
    }
}
