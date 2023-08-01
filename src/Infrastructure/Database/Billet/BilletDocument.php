<?php

namespace App\Infrastructure\Database\Billet;

use App\Domain\Billet\Billet;
use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @MongoDB\Document(collection="billets")
 */
class BilletDocument 
{

    /**
     * @MongoDB\Id(strategy="UUID")
     */
    protected string $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $billetId;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\UniqueIndex(order="asc")
     */
    protected string $digitableLine;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\UniqueIndex(order="asc")
     */
    protected string $barCode;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $fileId;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $name;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $governmentId;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $email;

    /**
     * @MongoDB\Field(type="integer")
     */
    protected int $debtAmount;

    /**
     * @MongoDB\Field(type="date")
     */
    protected DateTime $debtDueDate;

    /**
     * @MongoDB\Field(type="integer")
     * @MongoDB\UniqueIndex(order="asc")
     */
    protected int $debtId;

    public function __construct(Billet $billet)
    {
        $this->billetId = Uuid::uuid4();
        $this->barCode = $billet->getBarCode();
        $this->digitableLine = $billet->getDigitableLine();
        $this->name = $billet->getName();
        $this->email = $billet->getEmail();
        $this->governmentId = $billet->getGovernmentId();
        $this->debtAmount = $billet->getDebtAmount();
        $this->debtDueDate = $billet->getDebtDueDate();
        $this->debtId = $billet->getDebtId();
    }

    public function getBilletId(): string
    {
        return $this->billetId;
    }

    public function getBarCode(): string
    {
        return $this->barCode;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDigitableLine(): string
    {
        return $this->digitableLine;
    }

    public function setFileId(string $fileId)
    {
        $this->fileId = $fileId;
    }

}