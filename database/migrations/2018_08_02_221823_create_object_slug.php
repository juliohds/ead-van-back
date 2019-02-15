<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_slug', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('main_object_id');
            $table->foreign('main_object_id')->references('id')->on('main_object');
            $table->string('slug',1000)->unique();
            $table->index(['slug']);
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
        Schema::dropIfExists('object_slug');
    }
}
