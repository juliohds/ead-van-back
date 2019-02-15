<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassPlanOdaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_plan_oda', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('main_object_id')->nullable();
            $table->foreign('main_object_id')->references('id')->on('main_object');
            $table->integer('class_plan_id');
            $table->foreign('class_plan_id')->references('id')->on('class_plan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_plan_oda');
    }
}
