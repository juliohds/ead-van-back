<?php
namespace App\Exceptions;

class InvalidArgumentException extends \Exception {
    public function __construct() {
        parent::__construct("InvalidArgumentException: value param is invalid for this function");
    }
}