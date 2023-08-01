<?php

namespace App\Domain\File\Exception;

class FileAlreadyExistsException extends FileValidationException
{
    private const MESSAGE = 'Given filename: "%s" already exists';

    public function __construct(string $filename)
    {
        parent::__construct(sprintf(self::MESSAGE, $filename));
    }

    public function getExceptionCode(): string
    {
        return '02';
    }
}
