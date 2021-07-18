<?php

namespace Padua\CsvImporter\Service;

use Padua\CsvImporter\Dto\BankTransactionDto;

class CsvParser
{
    /**
     * @var TransactionCodeCheckerService
     */
    private $transactionCodeCheckerService;

    public function __construct(
        TransactionCodeCheckerService $transactionCodeCheckerService
    )
    {
        $this->transactionCodeCheckerService = $transactionCodeCheckerService;
    }

    public function parseCsv(
        string $filePath
    ): array
    {
        $counter = 0;

        //using PHP function fgetcsv and parse the CSV file
        $handle = fopen($filePath, 'r');
        while ($csvRow = fgetcsv($handle)) {

            //skip the first line of the CSV as this is usually headers
            $counter++;
            if ($counter === 1) {
                continue;
            }

            //map the CSV row to the bank transaction dto
            $bankTransactionDto = BankTransactionDto::decodeData($csvRow);

            //see if transaction code is valid
            $isValidTransaction = $this->transactionCodeCheckerService->verifyTransactionCode($bankTransactionDto->getTransactionCode());
            if ($isValidTransaction) {
                $bankTransactionDto->setValidTransation('Yes');
            } else {
                $bankTransactionDto->setValidTransation('No');
            }
            $bankTransactionDtos[] = $bankTransactionDto;
        }

        return $bankTransactionDtos;
    }
}