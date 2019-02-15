<?php

namespace App;


class Profile extends Entity
{
    const STUDENT = 1;
    const TEACHER = 2;
    const COORDINATOR = 3;
    const MANAGER = 4;
    const PARENT = 5;
    const OTHER = 6;

    protected $table = 'profile';
    protected $hidden = array('created_at', 'updated_at');

    public static function defaultValue($id){
        switch ($id) {
            case Profile::STUDENT:
                return 'student';
            case Profile::TEACHER:
                return 'teacher';
            case Profile::COORDINATOR:
                return 'coordinator';
            case Profile::MANAGER:
                return 'manager';
            case Profile::PARENT:
                return 'parent';
            case Profile::OTHER:
                return 'other';
            default:
                return null;
        }
    }

}
