<?php

namespace App\Application\File;

use App\Domain\File\File;
use App\Domain\File\FileDownloader;
use App\Domain\File\FileProcessedEventSender;
use App\Domain\File\FileRepository;
use App\Domain\File\FileStatus;
use Exception;

class ProcessFileService
{
    public function __construct(
        private FileDownloader $fileDownloader,
        private FileRepository $fileRepository,
        private CsvReader $csvReader,
        private FileProcessedEventSender $fileProcessedEventSender,
    ) {
        $this->fileDownloader = $fileDownloader;
        $this->fileRepository = $fileRepository;
        $this->csvReader = $csvReader;
        $this->fileProcessedEventSender = $fileProcessedEventSender;
    }


    public function processFile(string $fileId): void
    {
        $file = $this->fileRepository->findOneByFileId($fileId);
        if (FileStatus::PROCESS_PENDING !== $file->getStatus()) {
            //TODO throw exception
        }
        
        $file->setStatus(FileStatus::PROCESSING);
        $this->fileRepository->updateStatus($file);

        $this->fileDownloader->download($file);

        try {
            $totalLines = $this->csvReader->read($file);
        } catch (Exception $e) {
            $this->setFileProcessingError($file);
            throw $e;
        }

        $file->setTotalLines($totalLines);
        $this->setFileProcessed($file);
        $this->fileProcessedEventSender->send($file);
        //TODO move file to /PROCESSED folder
    }

    private function setFileProcessed(File $file): void
    {
        assert(null !== $file->getTotalLines());
        $file->processed();
        $this->fileRepository->setFileProcessed($file);
    }

    public function setFileProcessingError(File $file): void
    {
        $file->processingError();
        $this->fileRepository->updateStatus($file);
    }
}