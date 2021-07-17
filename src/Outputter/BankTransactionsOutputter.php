<?php

namespace Padua\CsvImporter\Outputter;

use Padua\CsvImporter\Dto\BankTransactionDto;
use Padua\CsvImporter\ValueObject\MoneyValueObject;

class BankTransactionsOutputter
{
    public function JSON()
    {

    }

    public function HTML()
    {

    }

    public function arrayForTwig(array $bankTransactionDtos) :array
    {
        $transactions = [];
        /** @var BankTransactionDto $bankTransactionDto */
        foreach ($bankTransactionDtos as $bankTransactionDto){
            $transaction = [];
            $transaction[] = $bankTransactionDto->getDate()->format("d/m/Y g:i A");
            $transaction[] = $bankTransactionDto->getTransactionCode();
            $transaction[] = 'Yes';
            $transaction[] = $bankTransactionDto->getCustomerNumber();
            $transaction[] = $bankTransactionDto->getReference();
            $transaction[] = MoneyValueObject::fromFloat($bankTransactionDto->getAmount())->toMoney();
            $transactionType = "Credit";
            if($bankTransactionDto->getAmount() > 0)
                $transactionType = "Debit";
            $transaction[] = $transactionType;

            $transactions[] = $transaction;
        }

        $transactions = $this->sortByDate($transactions, 0);

        return $transactions;
    }

    public function sortByDate(array $transactions, int $sortIndex) :array
    {
        $sortValues = [];
        foreach ($transactions as $key => $val) {
            $dateInfo = date_parse_from_format("d/m/Y g:iA", $val[$sortIndex]);

            $dt = new \DateTime();
            $dt->setDate($dateInfo['year'], $dateInfo['month'], $dateInfo['day'] );
            $dt->setTime($dateInfo['hour'], $dateInfo['minute'], $dateInfo['second']);

            $sortValues[$key] = $dt->getTimestamp();
        }

        array_multisort($sortValues, SORT_ASC, $transactions);

        return $transactions;
    }
}