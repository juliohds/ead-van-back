<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkConfigSocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_config_social', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_config_id');
            $table->foreign('network_config_id')->references('id')->on('network_config');
            $table->integer('type_social_id');
            $table->foreign('type_social_id')->references('id')->on('type_social');
            $table->string('url',1000);
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
        Schema::dropIfExists('network_config_social');
    }
}
