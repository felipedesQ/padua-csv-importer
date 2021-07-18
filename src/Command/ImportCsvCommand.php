<?php

namespace Padua\CsvImporter\Command;

use Padua\CsvImporter\Dto\BankTransactionDto;
use Padua\CsvImporter\Exception\Exception;
use Padua\CsvImporter\Outputter\BankTransactionsOutputter;
use Padua\CsvImporter\Service\TransactionCodeCheckerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCsvCommand extends Command
{
    const FILE_NAME = 'fileName';

    /**
     * @var string
     */
    private $uploadLocation;

    /**
     * @var BankTransactionsOutputter
     */
    private $bankTransactionsOutputter;


    /**
     * @var TransactionCodeCheckerService
     */
    private $transactionCodeCheckerService;

    public function __construct(
        string $uploadLocation,
        TransactionCodeCheckerService $transactionCodeCheckerService,
        BankTransactionsOutputter $bankTransactionsOutputter
    )
    {
        parent::__construct();

        $this->uploadLocation = $uploadLocation;
        $this->bankTransactionsOutputter = $bankTransactionsOutputter;
        $this->transactionCodeCheckerService = $transactionCodeCheckerService;
    }

    protected function configure()
    {
        $this
            ->setName('padua:csv:import')
            ->setDescription('Parse the CSV columns and rows into an object, sort the objects, ensure the Transaction Code is valid')
            ->addArgument(self::FILE_NAME, InputArgument::REQUIRED, 'Location of CSV file to import from');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $this->uploadLocation . $input->getArgument(self::FILE_NAME);

        //check if file exists
        if (!file_exists($filePath)) {
            throw new Exception('Missing file or unable to open ' . $filePath);
        }

        $counter = 0;
        $bankTransactionDtos = [];

        //using PHP function fgetcsv and parse the CSV file
        $handle = fopen($filePath, 'r');
        while ($csvRow = fgetcsv($handle)) {

            //skip the first line of the CSV as this is usually headers
            $counter++;
            if ($counter === 1) {
                $output->writeln('Skipping header line');
                continue;
            }

            //map the CSV row to the bank transaction dto
            $bankTransactionDto = BankTransactionDto::decodeData($csvRow);

            //see if transaction code is valid
            $isValidTransaction = $this->transactionCodeCheckerService->verifyTransactionCode($bankTransactionDto->getTransactionCode());
            if ($isValidTransaction) {
                $bankTransactionDtos[] = $bankTransactionDto;
            }
        }

        //generate the display table for the valid transactions
        $transactionDetails = $this->bankTransactionsOutputter->toArray($bankTransactionDtos);

        //generate html output
        $htmlOutput = $this->bankTransactionsOutputter->toHTML($transactionDetails);

        $output->writeln($htmlOutput);

        return 0;
    }
}
