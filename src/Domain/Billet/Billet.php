<?php

namespace App\Domain\Billet;

use DateTime;

class Billet
{

    public function __construct(
        private string $barCode,
        private string $digitableLine,
        private string $fileId,
        private string $name,
        private string $governmentId,
        private string $email,
        private int $debtAmount,
        private DateTime $debtDueDate,
        private int $debtId,
    )
    {
        $this->barCode = $barCode;
        $this->digitableLine = $digitableLine;
        $this->fileId = $fileId;
        $this->name = $name;
        $this->governmentId = $governmentId;
        $this->email = $email;
        $this->debtAmount = $debtAmount;
        $this->debtDueDate = $debtDueDate;
        $this->debtId = $debtId;
    }

    public function getBarCode(): string
    {
        return $this->barCode;
    }

    public function getDigitableLine(): string
    {
        return $this->digitableLine;
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getGovernmentId(): string
    {
        return $this->governmentId;
    }

    public function getDebtAmount(): int
    {
        return $this->debtAmount;
    }

    public function getDebtDueDate(): DateTime
    {
        return $this->debtDueDate;
    }

    public function getDebtId(): int
    {
        return $this->debtId;
    }
}