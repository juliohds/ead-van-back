<?php
namespace App\Libs;

class QueryOption {
    public $type;
    public $key;
    public $value;
    public $not;
    public $or;

    public function __construct($type,$key,$value,$not = false,$or = false){
        $this->type = $type;
        $this->key = $key;
        $this->value = $value;
        $this->not = $not;
        $this->or = $or;
    }
}
