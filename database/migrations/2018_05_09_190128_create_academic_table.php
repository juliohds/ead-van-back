<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('school');
            $table->string('school_name')->nullable();
            $table->integer('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('city');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('person', function (Blueprint $table) {
            $table->integer('academic_id')->nullable();
            $table->foreign('academic_id')->references('id')->on('academic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('person', function (Blueprint $table) {
            $table->dropForeign(['academic_id']);
        });
        Schema::dropIfExists('academic');
    }
}
