<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkFacetOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_facet_option', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_facet_id');
            $table->foreign('network_facet_id')->references('id')->on('network_facet');
            $table->integer('facet_option_id');
            $table->foreign('facet_option_id')->references('id')->on('facet_option');
            $table->string('title',100)->nullable();
            $table->string('picture',500)->nullable();
            $table->smallInteger('ui_index')->nullable()->default(1000);
            $table->boolean('enabled')->default(true);
            $table->unique(['network_facet_id','title']);
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
        Schema::dropIfExists('network_facet_option');
    }
}
