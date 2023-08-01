<?php

namespace App\Application\File;

use App\Domain\File\File;

class FileResponse
{

    private string $id;

    private string $status;

    private string $hash;

    public function __construct(File $file)
    {
        $this->id = $file->getId();
        $this->status = $file->getStatus();
        $this->hash = $file->getHash();
    }

    // public function serialize(): string
    // {
    //     return serialize([
    //         'filename' => $this->getName()
    //     ]);
    // }

    // public function unserialize($data)
    // {
    //     $unserializedData = unserialize($data);
    //     //TODO
    // }

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

    public function getHash(): string
    {
        return $this->hash;
    }

    public function processed(): void
    {
        $this->status = FileStatus::PROCESSED;
    }
}
