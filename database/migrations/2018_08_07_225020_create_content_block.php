<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentBlock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_block', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url',1000);
            $table->string('title',1000);
            $table->string('image',1000);
            $table->string('body',1000);
            $table->integer('content_id');
            $table->foreign('content_id')->references('id')->on('content');
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
        Schema::dropIfExists('content_block');
    }
}
