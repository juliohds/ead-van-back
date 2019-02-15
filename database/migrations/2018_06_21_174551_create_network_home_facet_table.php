<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkHomeFacetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_home_facet', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('ui_index');
            $table->integer('network_config_id');
            $table->foreign('network_config_id')->references('id')->on('network_config');
            $table->integer('network_facet_id');
            $table->foreign('network_facet_id')->references('id')->on('network_facet');
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
        Schema::dropIfExists('network_home_facet');
    }
}
