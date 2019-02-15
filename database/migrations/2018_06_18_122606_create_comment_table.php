<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text', 400);
            $table->boolean('enabled')->default(true);
            $table->integer('network_user_id');
            $table->foreign('network_user_id')->references('id')->on('network_user');
            $table->integer('target_object_id');
            $table->foreign('target_object_id')->references('id')->on('target_object');
            $table->softDeletes();
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
        Schema::dropIfExists('comment');
    }
}
