<?php

namespace App;


class Role extends Entity
{
    const ADMIN = 1;
    const NET_ADMIN = 2;
    const REVISOR = 3;
    const CURATOR = 4;
    const USER = 5;


    protected $table = 'approle';
    protected $hidden = array('created_at', 'updated_at');


    public static function defaultValue($id){
        switch ($id) {
            case Role::ADMIN:
                return 'admin';
            case Role::NET_ADMIN:
                return 'net_admin';
            case Role::REVISOR:
                return 'revisor';
            case Role::CURATOR:
                return 'curator';
            case Role::USER:
                return 'user';
            default:
                return null;
        }
    }
}
