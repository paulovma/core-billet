<?php

namespace App\Infrastructure\Database\File;

use App\Domain\File\File;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Ramsey\Uuid\Uuid;

/**
 * @MongoDB\Document(collection="files")
 */
class FileDocument 
{

    /**
     * @MongoDB\Id(strategy="UUID")
     */
    protected string $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $fileId;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\UniqueIndex(order="asc")
     */
    protected string $filename;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $status;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\UniqueIndex(order="asc")
     */
    protected string $hash;

    /**
     * @MongoDB\Field(type="integer")
     */
    protected ?int $totalLines;

    public function __construct(File $file)
    {
        $this->filename = $file->getName();
        $this->fileId = Uuid::uuid4();
        $this->status = $file->getStatus();
        $this->hash = $file->getHash();
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setTotalLines(int $totalLines): void
    {
        $this->totalLines = $totalLines;
    }

    public function toDomain(): File
    {
        $file = new File(
            $this->filename,
            $this->hash,
        );
        $file->setStatus($this->status);
        $file->setId($this->fileId);

        return $file;
    }

}