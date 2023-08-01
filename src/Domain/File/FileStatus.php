<?php

namespace App\Domain\File;

abstract class FileStatus
{
    public const PROCESS_PENDING = 'PROCESS_PENDING';
    public const PROCESSED = 'PROCESSED';
    public const PROCESSING = 'PROCESSING';
    public const PROCESSING_ERROR = 'PROCESSING_ERROR';
    public const ALL_STATUSES = [
        self::PROCESS_PENDING,
        self::PROCESSED,
        self::PROCESSING,
        self::PROCESS_PENDING,
    ];
}