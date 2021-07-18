<?php

namespace Padua\CsvImporter\Entity;

class BankTransactionEntity
{
    const MAX_LENGTH = 9;

    public function generateCheckCode(string $transactionCode): string
    {
        //generate code format
        return strtoupper(substr($transactionCode, 0, self::MAX_LENGTH));
    }
}
