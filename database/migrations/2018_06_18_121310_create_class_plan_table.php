<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan_process',5000);
            $table->string('goal',5000);
            $table->string('duration',500);
            $table->string('required_supplies',5000);
            $table->string('evaluation',5000);
            $table->string('activity_student',1000)->nullable();
            $table->string('activity_teacher',1000)->nullable();
            $table->string('following_teaching',1000)->nullable();
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
        Schema::dropIfExists('class_plan');
    }
}
