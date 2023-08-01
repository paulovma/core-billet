<?php

namespace App\Domain\File\Validator;

use App\Domain\File\Exception\FileAlreadyExistsException;
use App\Domain\File\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileAlreadyExistsValidator implements Validator
{
    private FileRepository $fileRepository;

    public function __construct(FileRepository $repository)
    {
        $this->fileRepository = $repository;
    }

    /**
     * Validates if given uploaded file already exists
     * 
     * @param UploadedFile $uploadedFile the uploaded file
     * 
     * @throws FileAlreadyExistsException when given uploaded file already exists in database
     */
    public function validate(UploadedFile $uploadedFile): void
    {
        $filename = $uploadedFile->getClientOriginalName();
        $exists = $this->fileRepository->exists($filename);

        if (true === $exists) {
            throw new FileAlreadyExistsException($filename);
        }
    }
}