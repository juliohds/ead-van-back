<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_list', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('title',500);     
            $table->string('description',1000)->nullable();
            $table->boolean('is_public')->nullable()->default(true);
            $table->integer('network_user_id');
            $table->foreign('network_user_id')->references('id')->on('network_user');
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
        Schema::dropIfExists('user_list');
    }
}
