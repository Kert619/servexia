<?php

namespace App\Exceptions;

use Exception;

class ValidationNotImplementedException extends Exception
{
    public function __construct(string $modelName, string $action)
    {
        parent::__construct("{$action} validation not implemented for {$modelName}");
    }
}
