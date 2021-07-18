<?php

namespace Padua\CsvImporter\Service;

use Padua\CsvImporter\Entity\BankTransactionEntity;

class TransactionCodeCheckerService
{

    /**
     * @var BankTransactionEntity
     */
    private $bankTransactionEntity;

    /**
     * @var CheckCharacterService
     */
    private $checkCharacterService;

    public function __construct(
        BankTransactionEntity $bankTransactionEntity,
        CheckCharacterService $checkCharacterService
    )
    {
        $this->bankTransactionEntity = $bankTransactionEntity;
        $this->checkCharacterService = $checkCharacterService;
    }

    public function verifyTransactionCode(string $transactionCode): bool
    {
        if (strlen($transactionCode) != 10) {
            return false;
        }

        $formattedCode = $this->bankTransactionEntity->generateCheckCode($transactionCode);
        $checkDigit = $this->checkCharacterService->generateCheckCharacter($formattedCode);

        return ($transactionCode[9] == $checkDigit);
    }


}