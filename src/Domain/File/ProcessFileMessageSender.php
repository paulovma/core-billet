<?php

namespace App\Domain\File;

interface ProcessFileMessageSender
{
    public function send(File $file);
}
