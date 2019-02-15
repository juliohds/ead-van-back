<?php
namespace App\Exceptions;

class TodoException extends \Exception {
    public function __construct() {
        parent::__construct("TodoException. This method is not implemented yet");
    }
}