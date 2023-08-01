<?php

namespace App\Infrastructure\S3\File;

use App\Domain\File\File;
use App\Domain\File\FileDownloader;
use Aws\S3\S3Client;

class S3Downloader implements FileDownloader
{
    private S3Client $s3Client;
    private string $bucketName;

    public function __construct(S3Client $s3Client, string $bucketName)
    {
        $this->s3Client = $s3Client;
        $this->bucketName = $bucketName;
    }

    public function download(File $file): File
    {
        $result = $this->s3Client->getObject([
            'Bucket' => $this->bucketName,
            'Key' => 'IDLE/' . $file->getName(),
        ]);

        $tempFilePath = tempnam(sys_get_temp_dir(), $file->getName());
        file_put_contents($tempFilePath, $result['Body']);

        $file->setPathName($tempFilePath);
        return $file;
    }
}
