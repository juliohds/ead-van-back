<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_object', function (Blueprint $table) {
            $table->increments('id');
            $table->string('picture',500)->nullable();
            $table->string('tags',500)->nullable();
            $table->string('bncc_tags',500)->nullable();
            $table->boolean('bncc_ok')->default(false)->nullable();
            $table->string('title', 500)->nullable();
            $table->string('description', 5000)->nullable();
            $table->string('produced_by', 500)->nullable();
            $table->integer('target_object_id');
            $table->integer('oda_id');
            $table->string('oda_type');
            $table->integer('curator_main_id')->nullable();
            $table->foreign('curator_main_id')->references('id')->on('appuser');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('main_object', function ($table) {
            $table->foreign('target_object_id')->references('id')->on('target_object');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_object');
    }
}
