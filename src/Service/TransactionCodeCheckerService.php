<?php

namespace Padua\CsvImporter\Service;

use Padua\CsvImporter\Entity\BankTransactionEntity;

class TransactionCodeCheckerService
{

    const TRANSACTION_CODE_LENGTH = 10;
    const TRANSACTION_KEY_CHECK = 9;

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

    /**
     * @param string $transactionCode
     * @return bool
     */
    public function verifyTransactionCode(string $transactionCode): bool
    {
        //make sure transaction code is the the required length
        if (strlen($transactionCode) != self::TRANSACTION_CODE_LENGTH) {
            return false;
        }

        //format the code
        $formattedCode = $this->bankTransactionEntity->generateCheckCode($transactionCode);

        //generate the check character
        $checkCharacter = $this->checkCharacterService->generateCheckCharacter($formattedCode);

        return ($transactionCode[self::TRANSACTION_KEY_CHECK] == $checkCharacter);
    }


}