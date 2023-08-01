<?php

namespace App\Domain\File\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface Validator
{
    public function validate(UploadedFile $uploadedFile): void;
}
