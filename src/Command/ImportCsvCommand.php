<?php

namespace Padua\CsvImporter\Command;

use Padua\CsvImporter\Dto\BankTransactionDto;
use Padua\CsvImporter\Exception\Exception;
use Padua\CsvImporter\Outputter\BankTransactionsOutputter;
use Padua\CsvImporter\Service\CsvParser;
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
     * @var CsvParser
     */
    private $csvParser;

    public function __construct(
        string $uploadLocation,
        CsvParser $csvParser,
        BankTransactionsOutputter $bankTransactionsOutputter
    )
    {
        parent::__construct();

        $this->uploadLocation = $uploadLocation;
        $this->bankTransactionsOutputter = $bankTransactionsOutputter;
        $this->csvParser = $csvParser;
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

        $bankTransactionDtos = [];
        $bankTransactionDtos = $this->csvParser->parseCsv($filePath);

        //generate the display table for the valid transactions
        $transactionDetails = $this->bankTransactionsOutputter->toArray($bankTransactionDtos);

        //generate html output
        $htmlOutput = $this->bankTransactionsOutputter->toHTML($transactionDetails);

        //write the ouput to a html file
        $fileInfo = pathinfo($input->getArgument(self::FILE_NAME));
        $writeToFile = $this->uploadLocation . $fileInfo['filename'] . '.html';

        $fp = fopen($writeToFile, 'w');
        fwrite($fp, $htmlOutput);
        fclose($fp);

        $message = "HTML file generated and can be found at " . $writeToFile;
        $output->writeln($message);

        return 0;
    }
}
