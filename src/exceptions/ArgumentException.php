<?php

namespace shophy\tclexiang\exceptions;

class ArgumentException extends \Exception
{
    public function __construct($message, $paramName, $code = 0, Exception $previous = null) {
        parent::__construct("{$paramName} {$message}", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}