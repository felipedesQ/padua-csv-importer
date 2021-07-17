<?php

namespace Padua\CsvImporter\Dto;

class BankTransactionDto
{
    const DATE_KEY = 0;
    const TRANSACTION_CODE_KEY = 1;
    const CUSTOMER_NUMBER_KEY = 2;
    const REFERENCE_KEY = 3;
    const AMOUNT_KEY = 4;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    protected $transactionCode;

    /**
     * @var integer
     */
    protected $customerNumber;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var float
     */
    protected $amount;

    public static function decodeData(array $transactionData): self
    {
        $transactionDto = new self();

        $transactionDto->date = $transactionData[self::DATE_KEY];
        $transactionDto->transactionCode = $transactionData[self::TRANSACTION_CODE_KEY];
        $transactionDto->customerNumber = $transactionData[self::CUSTOMER_NUMBER_KEY];
        $transactionDto->reference = $transactionData[self::REFERENCE_KEY];
        $transactionDto->amount = $transactionData[self::AMOUNT_KEY];

        return $transactionDto;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'transactionCode' => $this->transactionCode,
            'customerNumber' => $this->customerNumber,
            'reference' => $this->reference,
            'amount' => $this->amount
        ];
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getTransactionCode(): string
    {
        return $this->transactionCode;
    }

    /**
     * @return int
     */
    public function getCustomerNumber(): int
    {
        return $this->customerNumber;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

}
