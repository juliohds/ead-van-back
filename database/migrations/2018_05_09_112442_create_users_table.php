<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appuser', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login')->unique();
            $table->boolean('enabled')->default(true);
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('picture',500)->nullable();
            $table->string('old_id')->nullable();
            $table->string('refresh_token',255)->nullable();
            $table->bigInteger('change_password_token')->nullable();
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
        Schema::dropIfExists('appuser');
    }
}
