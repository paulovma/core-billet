<?php

namespace App\Infrastructure\Database\File;

use App\Domain\File\Exception\FileContentAlreadyProcessedException;
use App\Domain\File\File;
use App\Domain\File\FileRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;

class MongoAdapter implements FileRepository
{
    private DocumentManager $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function save(File $file): File
    {
        try {
            $fd = new FileDocument($file);
    
            $this->dm->persist($fd);
            $this->dm->flush();
    
            $file->setId($fd->getFileId());
            return $file;
        } catch (Exception $e) {
            if (11000 === $e->getCode()) {
                throw new FileContentAlreadyProcessedException($file->getHash());
            }
            throw $e;
        }
    }

    public function setFileProcessed(File $file): void
    {
        $fd = $this->findOneBy(['fileId' => $file->getId()]);
        $fd->setTotalLines($file->getTotalLines());
        $fd->setStatus($file->getStatus());
        $this->dm->flush();
    }


    public function exists(string $filename): bool
    {
        $doc = $this->findOneBy(['filename' => $filename]);
        return null !== $doc;
    }

    public function findOneByFileId(string $fileId): File
    {
        $fd = $this->findOneBy(['fileId' => $fileId]);
        return $fd->toDomain();
    }

    private function findOneBy(array $criteria): ?FileDocument
    {
        return $this->dm->getRepository(FileDocument::class)->findOneBy($criteria);
    }

    public function updateStatus(File $file): void
    {
        $fd = $this->findOneBy(['fileId' => $file->getId()]);
        $fd->setStatus($file->getStatus());

        $this->dm->flush();
    }

}