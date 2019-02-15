<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_slug', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('custom_page_id');
            $table->foreign('custom_page_id')->references('id')->on('custom_page');
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
        Schema::dropIfExists('page_slug');
    }
}
