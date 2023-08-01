<?php

namespace App\Application\File;

use App\Application\Billet\BilletService;
use App\Domain\File\File;
use App\Domain\File\FileProcessedEventSender;
use Exception;

class CsvReader
{
    private const CHUNK_PERSIST = 1000;

    public function __construct(
        private BilletService $billetService,
        private CreateFileService $createFileService,
    ) {
        $this->billetService = $billetService;
        $this->createFileService = $createFileService;
    }

    /**
     * Reads CSV file
     * Stream the file creating Billet and inserting to Database
     * 
     * @return int the number of processed rows
     */
    public function read(File $file): int
    {
        $billets = [];
        $count = 0;

        $csvData = $this->readCsv($file);
        
        for($i = 1; $i < sizeof($csvData); $i++)
        {
            $count++;
            $data = $csvData[$i];
            $billet = $this->billetService->buildBillet($data, $file);

            $billets[] = $billet;
            
            if (self::CHUNK_PERSIST === sizeof($billets)) {
                $this->billetService->insertChunk($billets);
                $billets = [];
            }
        }

        if (0 < sizeof($billets)) {
            $this->billetService->insertChunk($billets);
        }

        return $count;
    }

    private function readCsv(File $file): array
    {
        $csvData = [];
        if (($file = fopen($file->getPathName(), 'r')) !== false) {
            while (($data = fgetcsv($file)) !== false) {
                $csvData[] = $data;
            }
            fclose($file);
        } else {
            throw new Exception('File not found');
        }

        return $csvData;
    }
}