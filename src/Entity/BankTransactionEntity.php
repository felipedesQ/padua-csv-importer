<?php

namespace Padua\CsvImporter\Entity;

class BankTransactionEntity
{
    public function generateCheckCode(string $transactionCode) :string
    {
        return strtoupper(substr($transactionCode, 0, 9));
    }
}
