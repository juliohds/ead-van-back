<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOldDbFacetOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_db_facet_option', function (Blueprint $table) {
            $table->increments('id');
            $table->string('field_name');
            $table->string('old_id');
            $table->integer('new_id')->nullable();
            $table->boolean('is_description')->default(false);
            $table->string('description',5000)->nullable();
            $table->foreign('new_id')->references('id')->on('facet_option');
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
        Schema::dropIfExists('old_db_facet_option');
    }
}
