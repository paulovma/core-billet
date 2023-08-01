<?php

namespace App\Domain\Exception;

use Exception;

abstract class BusinessException extends Exception
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public abstract function getExceptionCode(): string;
}
