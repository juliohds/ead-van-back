<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NetworkConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_config', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('use_revisor')->default(false);
            warningTODO("Some network's configs is unknown",__LINE__,__FILE__);
            //$table->boolean('active_profile_poll');
            //$table->boolean('active_satisfaction_poll')->default(false);
            $table->boolean('allow_anonymous_oda_suggestion')->default(false);
            //$table->boolean('allow_children_to_collaborate')->default;
            $table->boolean('allow_class_plan')->default(true);
            $table->boolean('allow_sign_in')->default(true);
            $table->boolean('automatic_approve_comments')->default(false);
            $table->string('contact_email',500)->nullable();
            //$table->string('content_about_the_plataform',500);
            //$table->string('content_common_odas',500);
            //$table->string('content_cooperate_title',500);
            //$table->string('content_how_to_start',500);
            //$table->string('content_lists',500);
            //$table->string('content_main_contributors',500);
            //$table->string('content_network_id',500);
            //$table->string('content_professor_content',500);
            //$table->string('content_sign_in',500);
            //$table->boolean('customize_admin_menu');
            //$table->string('filter_text',500);
            //$table->boolean('filters_always_open');
            $table->string('ga_code',500)->nullable();
            //$table->string('ga_code_admin',500);
            //$table->string('highlight_text',500);
            //$table->boolean('infinite_scroll');
            $table->boolean('is_provider')->default(false);
            $table->boolean('lists_active')->default(false);
            $table->boolean('main_contributors_active')->default(true);
            $table->boolean('mapa_active')->default(true);
            $table->boolean('filter_home_active')->default(true);
            $table->boolean('oda_suggest_active')->default(true);
            //$table->integer('max_profile_poll_attempts');
            //$table->integer('min_profile_age_for_satisfaction_poll');
            //$table->integer('min_sign_in_count_for_satisfaction_poll');
            $table->boolean('popular_objects_active')->default(false);
            //$table->boolean('refine_on_click');
            //$table->boolean('review_oda');
            $table->string('slogan',500)->nullable();
            //$table->string('subtitle_highlight_text',500);
            $table->boolean('suggest_objects_active')->default(false);
            $table->string('suggest_thanks',500)->nullable();
            $table->boolean('survey_enabled')->default(false);
            $table->string('survey_thanks_1',500)->nullable();
            $table->string('survey_thanks_2',500)->nullable();
            $table->string('survey_url',500)->nullable();
            $table->string('url_logo',500)->nullable();
            $table->string('url_imagem_principal',500)->nullable();
            $table->string('cor_primaria',30)->nullable();
            $table->string('cor_secundaria',30)->nullable();
            $table->string('text-color_primaria',30)->nullable();
            $table->string('url_facebook')->nullable();
            $table->boolean('odas_populares')->default(false);
            $table->string('url_youtube')->nullable();
            $table->boolean('sugestao_odas')->default(false);
            $table->boolean('listas')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('network', function (Blueprint $table) {
            $table->integer('network_config_id')->nullable();
            $table->foreign('network_config_id')->references('id')->on('network_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('network', function (Blueprint $table) {
            $table->DropForeign(['network_config_id']);
        });

        Schema::drop('network_config');
    }
}
