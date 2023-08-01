<?php

namespace App\Domain\File\Validator;

use App\Domain\File\Exception\FileSizeExcededException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSizeValidator implements Validator
{
    private const MAX_FILE_SIZE = 1024 * 1024 * 1024;
    public const MAX_FILE_SIZE_DESC = '1GB';

    /**
     * Validates if given uploaded file exceeds maximum size allowed
     * 
     * @param UploadedFile $uploadedfile
     * 
     * @throws FileSizeExcededException when filesize exceeds maximum allowed
     */
    public function validate(UploadedFile $uploadedFile): void
    {
        if (self::MAX_FILE_SIZE < $uploadedFile->getSize()) {
            throw new FileSizeExcededException(
                $uploadedFile->getClientOriginalName(),
                self::MAX_FILE_SIZE_DESC
            );
        }
    }

}