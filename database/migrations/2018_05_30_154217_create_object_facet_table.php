<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFacetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_facet_option', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('main_object_id');
            $table->foreign('main_object_id')->references('id')->on('main_object');
            $table->integer('facet_option_id');
            $table->foreign('facet_option_id')->references('id')->on('facet_option');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('object_facet_option');
    }
}
