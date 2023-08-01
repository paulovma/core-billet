<?php

namespace App\Infrastructure\SQS\File;

use App\Domain\File\File;
use App\Domain\File\ProcessFileMessageSender;
use Aws\Sqs\SqsClient;

class SQSMessageSender implements ProcessFileMessageSender
{
    private SqsClient $sqsClient;
    private string $queueUrl;

    public function __construct(SqsClient $sqsClient, string $queueUrl)
    {
        $this->sqsClient = $sqsClient;
        $this->queueUrl = $queueUrl;
    }

    public function send(File $file): void
    {
        $this->sqsClient->sendMessage([
            'QueueUrl' => $this->queueUrl,
            'MessageBody' => json_encode(['fileId' => $file->getId()]),
        ]);
    }
}