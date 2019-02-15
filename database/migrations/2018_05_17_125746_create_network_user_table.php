<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_id');
            $table->foreign('network_id')->references('id')->on('network');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('appuser');
            $table->smallInteger('role_id');
            $table->foreign('role_id')->references('id')->on('approle');
            $table->unique(['network_id', 'user_id']);
            $table->index(['network_id', 'user_id']);
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
        Schema::dropIfExists('network_user');
    }
}
