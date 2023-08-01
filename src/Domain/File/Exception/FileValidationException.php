<?php

namespace App\Domain\File\Exception;

use App\Domain\Exception\BusinessException;

abstract class FileValidationException extends BusinessException
{
    
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
