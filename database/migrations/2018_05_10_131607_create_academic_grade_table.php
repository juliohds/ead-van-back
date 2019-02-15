<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_grade', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_id');
            $table->foreign('academic_id')->references('id')->on('academic');
            $table->integer('grade_id');
            $table->foreign('grade_id')->references('id')->on('grade');
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
        Schema::dropIfExists('academic_grade');
    }
}
