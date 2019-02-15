<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicInterestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_interest', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_id');
            $table->foreign('academic_id')->references('id')->on('academic');
            $table->integer('interest_id');
            $table->foreign('interest_id')->references('id')->on('interest');
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
        Schema::dropIfExists('academic_interest');
    }
}
