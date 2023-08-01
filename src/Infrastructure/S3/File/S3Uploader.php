<?php

namespace App\Infrastructure\S3\File;

use App\Domain\File\File;
use App\Domain\File\FileStorer;
use Aws\S3\S3Client;

class S3Uploader implements FileStorer
{
    private S3Client $s3Client;
    private string $bucketName;
    private const PATH = 'IDLE/';

    public function __construct(S3Client $s3Client, string $bucketName)
    {
        $this->s3Client = $s3Client;
        $this->bucketName = $bucketName;
    }

    public function store(File $file): void
    {
        $fileContent = file_get_contents($file->getPathname());
        $s3Key =  self::PATH . $file->getName();

        $this->s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $s3Key,
            'Body' => $fileContent,
            'ACL' => 'public-read',
        ]);
    }
}