<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkFacetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_facet', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_id');
            $table->foreign('network_id')->references('id')->on('network');
            $table->integer('facet_id');
            $table->foreign('facet_id')->references('id')->on('facet');
            $table->string('title',100)->nullable();
            $table->smallInteger('ui_index')->nullable();
            $table->boolean('suggestion_enabled')->default(false);
            $table->boolean('required')->default(false);
            $table->boolean('enabled')->default(false);
            $table->smallInteger('limit')->default(0);
            $table->unique(['facet_id','network_id']);
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
        Schema::dropIfExists('network_facet');
    }
}
