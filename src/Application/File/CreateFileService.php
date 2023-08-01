<?php

namespace App\Application\File;

use App\Domain\File\File;
use App\Domain\File\FileRepository;
use App\Domain\File\FileStorer;
use App\Domain\File\ProcessFileMessageSender;
use App\Domain\File\Validator\ValidatorChain;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateFileService
{
    private FileStorer $s3Uploader;
    private ProcessFileMessageSender $sqsMessageSender;
    private FileRepository $fileRepository;
    private ValidatorChain $validatorChain;

    public function __construct(
        FileStorer $s3Uploader,
        ProcessFileMessageSender $sqsMessageSender,
        FileRepository $repository,
        ValidatorChain $validatorChain,
    ) {
        $this->s3Uploader = $s3Uploader;
        $this->sqsMessageSender = $sqsMessageSender;
        $this->fileRepository = $repository;
        $this->validatorChain = $validatorChain;
    }

    public function uploadCsvFile(UploadedFile $csvFile): FileResponse
    {
        $this->validatorChain->validate($csvFile);

        $hashedValue = hash_file('sha256', $csvFile->getPathname());
        $file = new File($csvFile->getClientOriginalName(), $hashedValue, $csvFile->getPathname());

        $file = $this->fileRepository->save($file);
        $this->s3Uploader->store($file);
        $this->sqsMessageSender->send($file);

        return new FileResponse($file);
    }

}