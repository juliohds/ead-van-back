<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gender', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',30);
            $table->timestamps();
            $table->softDeletes();        
        });

        Schema::table('person', function (Blueprint $table) {
            $table->smallInteger('gender_id')->nullable();
            $table->foreign('gender_id')->references('id')->on('gender');
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
            $table->dropForeign(['gender_id']);
        });
               
        Schema::dropIfExists('gender');
    }
}
