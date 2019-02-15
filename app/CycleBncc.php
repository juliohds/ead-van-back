<?php

namespace App;


class CycleBncc extends Entity
{
    const EI = 1;
    const EF = 2;
    const EM = 3;

    protected $table = 'cycle_bncc';
    protected $hidden = array('created_at', 'updated_at');

    public static function defaultValue($id){
        switch ($id) {
            case CycleBncc::EI:
                return 'Ensino Infantil';
            case CycleBncc::EF:
                return 'Ensino Fundamental';
            case CycleBncc::EM:
                return 'Ensino Médio';
            default:
                return null;
        }
    }

}
