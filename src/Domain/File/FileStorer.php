<?php

namespace App\Domain\File;

interface FileStorer
{
    public function store(File $file): void;
}
