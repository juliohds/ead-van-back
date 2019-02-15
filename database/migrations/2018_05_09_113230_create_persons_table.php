<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->dateTime('birth_date')->nullable();
            $table->string('instituition')->nullable();
            $table->string('cpf')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('wish_sms')->nullable();

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
        Schema::table('appuser', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
        });
               
        
        Schema::dropIfExists('person');
    }
}
