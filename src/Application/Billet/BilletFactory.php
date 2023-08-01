<?php

namespace App\Application\Billet;

use App\Domain\Billet\Billet;
use App\Domain\File\File;
use DateTime;
use Ramsey\Uuid\Uuid;

class BilletFactory
{
    private const DATE_FORMAT = 'Y-m-d';

    public function makeFromCsvFile(array $data, File $file): Billet
    {
        $date = DateTime::createFromFormat(self::DATE_FORMAT, $data[4]);
        $billet = new Billet(
            Uuid::uuid4(),
            Uuid::uuid4(),
            $file->getId(),
            $data[0],
            $data[1],
            $data[2],
            $data[3] * 100,
            new DateTime($date->format('Y-m-d')),
            $data[5],
        );

        return $billet;
    }
}