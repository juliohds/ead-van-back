<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComponentBncc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('component_bncc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->smallInteger('cycle_bncc_id');
            $table->foreign('cycle_bncc_id')->references('id')->on('cycle_bncc');
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
        Schema::dropIfExists('component_bncc');
    }
}
