<?php

namespace App\Application\Billet;

use App\Domain\Billet\Billet;
use App\Domain\Billet\BilletRepository;
use App\Domain\File\File;

class BilletService
{
    private BilletFactory $billetFactory;
    private BilletRepository $billetRepository;

    public function __construct(BilletFactory $billetFactory, BilletRepository $billetRepository)
    {
        $this->billetFactory = $billetFactory;
        $this->billetRepository = $billetRepository;
    }

    public function insertChunk(array $billets): void
    {
        $this->billetRepository->insertChunk($billets);
    }

    public function buildBillet(array $data, File $file)
    {
        return $this->billetFactory->makeFromCsvFile($data, $file);
    }
}