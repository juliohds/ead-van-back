<?php

namespace App;

class NetworkConfig extends Entity
{
    protected $table = 'network_config';
    protected $hidden = array('created_at', 'updated_at');

    protected $fillable = [
        'use_revisor', 'allow_anonymous_oda_suggestion', 'allow_class_plan',
        'allow_sign_in', 'automatic_approve_comments', 'contact_email',
        'ga_code', 'is_provider', 'lists_active', 'main_contributors_active', 'oda_suggest_active', 'mapa_active', 'popular_objects_active',
        'slogan', 'suggest_objects_active', 'suggest_thanks', 'survey_enabled', 'filter_home_active',
        'survey_thanks_1', 'survey_thanks_2', 'survey_url', 'url_logo',
        'url_imagem_principal', 'cor_primaria', 'cor_secundaria', 'url_facebook', 'url_youtube', 'text_color_primaria',
        'network_config_menu_id', 'network_rede_social_id', 'odas_populares', 'text_color_secundaria',
        'sugestao_odas', 'listas', 'gtm_code'
    ];

    public function network() {
        return $this->hasOne('App\Network');
    }

    function menu() {
        return $this->belongsTo('App\Menu');
    }

    public function homeFacets() {
        return $this->belongsToMany('App\NetworkFacet','network_home_facet',
            'network_config_id','network_facet_id')->orderBy('ui_index','asc');
    }

    public function socials(){
        return $this->hasMany('App\NetworkConfigSocial');
    }
    public function institutionMenus(){
        return $this->hasMany('App\InstitutionMenu');
    }    
    public function footerMenus(){
        return $this->hasMany('App\FooterMenu')->orderBy('column','asc')->orderBy('ui_index','asc');
    }

}
