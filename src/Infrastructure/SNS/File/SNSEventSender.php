<?php

namespace App\Infrastructure\SNS\File;

use App\Domain\File\File;
use App\Domain\File\FileProcessedEventSender;
use Aws\Sns\SnsClient;
use DateTime;

class SNSEventSender implements FileProcessedEventSender
{
    private SnsClient $snsClient;
    private string $topicArn;

    public function __construct(SnsClient $snsClient, string $topicArn)
    {
        $this->snsClient = $snsClient;
        $this->topicArn = $topicArn;
    }

    public function send(File $file): void
    {
        $now = new DateTime();
        $this->snsClient->publish([
            'TopicArn' => $this->topicArn,
            'Message' => json_encode([
                'event_name' => 'FILE_PROCESSED',
                'event_date' => $now->format('Y-m-d H:i:s'),
                'data' => [
                    'file_id' => $file->getId(),
                    'total_lines' => $file->getTotalLines(),
                    'hash' => $file->getHash(),
                ]
            ]),
        ]);
    }
}