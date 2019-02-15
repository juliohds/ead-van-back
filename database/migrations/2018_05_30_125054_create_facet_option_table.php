<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacetOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facet_option', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',500);
            $table->string('picture',500)->nullable();
            $table->string('synonymous',1000)->nullable();
            $table->integer('facet_id');
            $table->foreign('facet_id')->references('id')->on('facet');
            $table->unique(['facet_id','title']);
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
        Schema::dropIfExists('facet_option');
    }
}
