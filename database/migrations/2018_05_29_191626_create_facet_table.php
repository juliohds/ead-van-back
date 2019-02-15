<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type',20)->unique()->nullable();
            $table->boolean('is_global')->default(true);
            $table->string('title',100)->unique();
            $table->string('help_text',255)->nullable();
            $table->string('synonymous',1000)->nullable();
            $table->smallInteger('facet_type_id');
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
        Schema::dropIfExists('facet');
    }
}
