<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url',1000);
            $table->string('image',1000);
            $table->integer('network_config_id');
            $table->foreign('network_config_id')->references('id')->on('network_config');
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
        Schema::dropIfExists('institution_menu');
    }
}
