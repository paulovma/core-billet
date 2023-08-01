<?php

namespace App\Domain\File;

interface FileRepository
{
    public function save(File $file): File;
    public function exists(string $filename): bool;
    public function findOneByFileId(string $fileId): File;
    public function updateStatus(File $file): void;
    public function setFileProcessed(File $file): void;
}