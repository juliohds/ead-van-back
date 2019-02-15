<?php
namespace App\Libs;

use App\Versionable;

class Comparator {

    //compare 2 versionables objects and return true if 
    //they have same content
    public static function compare(Versionable $a,Versionable $b){
        if(!$a || !$b){
            return false;
        }
        if($a->class !=  $b->class){
            return false;
        }

        foreach($a->comparableFields() as $field){
            if($b->$field instanceof Versionable){
                $v = Comparator::compare($a->$field,$b->$field);
                if(!$v){
                    return false;
                }
            }else if(is_array($a->$field) && is_array($b->$field)){
                
                $diff = count(array_diff($a->$field,$b->$field));
                if($diff>0){
                    return false;
                }
            
            }else{
                if($a->$field != $b->$field){
                    return false;
                }
            }
        }
        return true;
    }
}
