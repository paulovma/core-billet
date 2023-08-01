<?php

namespace App\Domain\File\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ValidatorChain
{

    private $validators;

    public function __construct(iterable $validators)
    {
        foreach($validators as $validator) {
            assert($validator instanceof Validator);
        }
        $this->validators = $validators;
    }

    public function validate(UploadedFile $uploadedFile): void
    {
        foreach($this->validators as $validator)
        {
            $validator->validate($uploadedFile);
        }
    }
}