<?php

namespace App\Domain\File\Exception;

class FileContentAlreadyProcessedException extends FileValidationException
{
    private const MESSAGE = 'Given file\'s content has already been processed. File content hash: %s';

    public function __construct(string $hash)
    {
        parent::__construct(sprintf(self::MESSAGE, $hash));
    }

    public function getExceptionCode(): string
    {
        return '03';
    }
}
