<?php

namespace App\Domain\File;

class File
{

    private string $name;

    private string $id;

    private string $status;

    private string $hash;

    private ?string $pathName;

    private ?int $totalLines;

    public function __construct(string $filename, string $hash, string $pathName = null)
    {
        $this->name = $filename;
        $this->status = FileStatus::PROCESS_PENDING;
        $this->hash = $hash;
        $this->pathName = $pathName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        assert(in_array($status, FileStatus::ALL_STATUSES));
        $this->status = $status;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getPathName(): ?string
    {
        return $this->pathName;
    }

    public function setPathName(string $path): void
    {
        $this->pathName = $path;
    }

    public function processed(): void
    {
        $this->status = FileStatus::PROCESSED;
    }

    public function processingError(): void
    {
        $this->status = FileStatus::PROCESSING_ERROR;
    }

    public function getTotalLines(): ?int
    {
        return $this->totalLines;
    }

    public function setTotalLines(int $totalLines): void
    {
        $this->totalLines = $totalLines;
    }
}
