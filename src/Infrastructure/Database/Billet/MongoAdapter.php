<?php

namespace App\Infrastructure\Database\Billet;

use App\Domain\Billet\Billet;
use App\Domain\Billet\BilletRepository;
use App\Domain\File\File;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;

class MongoAdapter implements BilletRepository
{
    private DocumentManager $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function insertChunk(array $billets): void
    {
        try {
            foreach($billets as $billet)
            {
                assert($billet instanceof Billet);
                $doc = new BilletDocument($billet);
                $doc->setFileId($billet->getFileId());
                $this->dm->persist($doc);
            }
            $this->dm->flush();
    
        } catch (Exception $e) {
            // if (11000 === $e->getCode()) {
            //     throw new FileContentAlreadyProcessedException($file->getHash());
            // }
            throw $e;
        }
    }

    // public function insertChunk(): void
    // {
    //     try {
    //         // foreach($billets as $billet)
    //         // {
    //         //     assert($billet instanceof Billet);
    //         //     $doc = new BilletDocument($billet);
    //         //     $documentPersister->addInsert($doc);
    //         // }
    //         $this->dp->executeInserts();
    
    //     } catch (Exception $e) {
    //         // if (11000 === $e->getCode()) {
    //         //     throw new FileContentAlreadyProcessedException($file->getHash());
    //         // }
    //         throw $e;
    //     }
    // }

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