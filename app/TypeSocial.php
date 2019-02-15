<?php

namespace App;

class TypeSocial extends Entity
{
    const FACEBOOK = 1;
    const TWITTER = 2;
    const GOOGLE_PLUS = 3;
    const YOUTUBE = 4;

    protected $table = 'type_social';

    public static function defaultValue($id){
        switch ($id) {
            case TypeSocial::FACEBOOK:
                return 'facebook';
            case TypeSocial::TWITTER:
                return 'twitter';
            case TypeSocial::GOOGLE_PLUS:
                return 'google+';
            case TypeSocial::YOUTUBE:
                return 'youtube';
            default:
                return null;
        }
    }
}
