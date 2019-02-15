<?php

namespace App;
use App\Exceptions\InvalidArgumentException;

class FacetType extends Entity
{
    const ODA = 1;
    const COURSE = 2;

    protected $table = 'facet_type';

    public static function defaultValue($id){
        switch ($id) {
            case FacetType::ODA:
                return 'oda';
            case FacetType::COURSE:
                return 'course';
            default:
                return null;
        }
    }
    public static function valueOf($name){
        switch ($name){
            case 'oda':
                return FacetType::ODA;
            case 'course':
                return FacetType::COURSE;
            default:
                throw new InvalidArgumentException;
        }
    }
}
