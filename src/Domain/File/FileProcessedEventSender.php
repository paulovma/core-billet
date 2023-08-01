<?php

namespace App\Domain\File;

interface FileProcessedEventSender
{
    public function send(File $file);
}
