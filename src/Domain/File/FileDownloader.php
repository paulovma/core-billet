<?php

namespace App\Domain\File;

interface FileDownloader
{
    public function download(File $file): File;
}
