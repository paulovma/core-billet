<?php

namespace App\Infrastructure\SQS\File;

use App\Application\File\CreateFileService;
use App\Application\File\ProcessFileService;
use Aws\Sqs\SqsClient;

class SQSMessageConsumer
{
    private SqsClient $sqsClient;
    private string $queueUrl;
    private ProcessFileService $fileService;

    public function __construct(SqsClient $sqsClient, string $queueUrl, ProcessFileService $fileService)
    {
        $this->sqsClient = $sqsClient;
        $this->queueUrl = $queueUrl;
        $this->fileService = $fileService;
    }

    public function consumeMessages(): void
    {
        while (true) {
            $result = $this->sqsClient->receiveMessage([
                'QueueUrl' => $this->queueUrl,
                'MaxNumberOfMessages' => 10,
                'WaitTimeSeconds' => 10,
            ]);

            if ($messages = $result->get('Messages')) {
                foreach ($messages as $message) {
                    $fileId = json_decode($message['Body'])->fileId;
                    $this->fileService->processFile($fileId);
                    $this->sqsClient->deleteMessage([
                        'QueueUrl' => $this->queueUrl,
                        'ReceiptHandle' => $message['ReceiptHandle'],
                    ]);
                }
            }
        }
    }

    private function processMessage(array $message): void
    {
        // Your custom message processing logic here
        // $message['Body'] contains the message body
    }
}
