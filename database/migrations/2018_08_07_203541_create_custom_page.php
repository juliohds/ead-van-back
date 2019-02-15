<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_page', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',500);
            $table->string('body',20000);
            $table->boolean('published')->default(false);
            $table->string('image',1000)->nullable();
            $table->integer('network_id');
            $table->foreign('network_id')->references('id')->on('network');
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
        Schema::dropIfExists('custom_page');
    }
}
