<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbilityBncc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ability_bncc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bncc',8);
            $table->string('description',2000);
            $table->smallInteger('start_year');
            $table->smallInteger('end_year');
            $table->integer('component_bncc_id');
            $table->foreign('component_bncc_id')->references('id')->on('component_bncc');
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
        Schema::dropIfExists('ability_bncc');
    }
}
