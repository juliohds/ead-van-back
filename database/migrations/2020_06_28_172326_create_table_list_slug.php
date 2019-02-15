<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableListSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_slug', function (Blueprint $table) {

            $table->increments('id');
            $table->string('url');
            $table->integer('user_list_id');
            $table->foreign('user_list_id')->references('id')->on('user_list');
            $table->unique(['url']);
            $table->index(['id']);
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
        Schema::dropIfExists('list_slug');
    }
}
