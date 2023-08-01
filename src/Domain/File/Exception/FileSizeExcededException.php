<?php

namespace App\Domain\File\Exception;

class FileSizeExcededException extends FileValidationException
{
    private const MESSAGE = 'Given file: "%s" exceeds max allowed: %s';

    public function __construct(string $filename, $filesize)
    {
        parent::__construct(sprintf(self::MESSAGE, $filename, $filesize));
    }

    public function getExceptionCode(): string
    {
        return '01';
    }
}
