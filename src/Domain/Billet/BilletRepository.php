<?php

namespace App\Domain\Billet;

interface BilletRepository
{
    public function insertChunk(array $billets): void;
}