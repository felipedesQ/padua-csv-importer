<?php

namespace Padua\CsvImporter\Outputter;

use Padua\CsvImporter\Dto\BankTransactionDto;
use Padua\CsvImporter\ValueObject\MoneyValueObject;
use Twig\Environment;

class BankTransactionsOutputter
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(
        Environment $twig
    )
    {
        $this->twig = $twig;
    }

    public function toJSON()
    {
        //TODO: output in  JSON format
    }

    public function toHTML(array $transactionDetails) :string
    {
        /**
        $htmlOutput = $this->twig->render('pages/bank-transactions.html.twig', [
            'transactionDetails' => $transactionDetails
        ]);
        /**/

        $htmlOutput = $this->twig->render('pages/bank-transactions-base.html.twig', [
            'transactionDetails' => $transactionDetails
        ]);

        return $htmlOutput;
    }

    public function toArray(array $bankTransactionDtos): array
    {
        $transactions = [];
        /** @var BankTransactionDto $bankTransactionDto */
        foreach ($bankTransactionDtos as $bankTransactionDto) {
            $transaction = [];
            $transaction[] = $bankTransactionDto->getDate()->format("d/m/Y g:i A");
            $transaction[] = $bankTransactionDto->getTransactionCode();
            $transaction[] = $bankTransactionDto->getValidTransaction();
            $transaction[] = $bankTransactionDto->getCustomerNumber();
            $transaction[] = $bankTransactionDto->getReference();
            $transaction[] = MoneyValueObject::fromFloat($bankTransactionDto->getAmount())->toMoney();
            $transactionType = "Credit";
            if ($bankTransactionDto->getAmount() > 0)
                $transactionType = "Debit";
            $transaction[] = $transactionType;

            $transactions[] = $transaction;
        }

        //sort the array by date asc
        $transactions = $this->sortByDate($transactions, 0);

        return $transactions;
    }

    public function sortByDate(array $transactions, int $sortIndex): array
    {
        $sortValues = [];
        foreach ($transactions as $key => $val) {
            //make sure datetime format so we can just sort it .. sort by timestamp is
            $dateInfo = date_parse_from_format("d/m/Y g:i A", $val[$sortIndex]);

            $dt = new \DateTime();
            $dt->setDate($dateInfo['year'], $dateInfo['month'], $dateInfo['day']);
            $dt->setTime($dateInfo['hour'], $dateInfo['minute'], $dateInfo['second']);

            $sortValues[$key] = $dt->getTimestamp();
        }

        array_multisort($sortValues, SORT_ASC, $transactions);

        return $transactions;
    }
}