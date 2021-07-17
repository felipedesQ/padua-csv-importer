<?php

namespace Padua\CsvImporter\Command;

use Padua\CsvImporter\Dto\BankTransactionDto;
use Padua\CsvImporter\Service\TransactionCodeCheckerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;

class ImportCsvCommand extends Command
{
    const FILE_NAME = 'fileName';

    /**
     * @var string
     */
    private $uploadLocation;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var TransactionCodeCheckerService
     */
    private $transactionCodeCheckerService;

    public function __construct(
        string $uploadLocation,
        TransactionCodeCheckerService $transactionCodeCheckerService,
        Environment $twig
    )
    {
        parent::__construct();

        $this->uploadLocation = $uploadLocation;
        $this->transactionCodeCheckerService = $transactionCodeCheckerService;
        $this->twig = $twig;
    }

    protected function configure()
    {
        $this
            ->setName('padua:csv:import')
            ->setDescription('Parse the CSV columns and rows into an object, sort the objects, ensure the Transaction Code is valid')
            ->addArgument(self::FILE_NAME, InputArgument::REQUIRED, 'Location of CSV file to import from')
        ;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $this->uploadLocation . $input->getArgument(self::FILE_NAME);

        if (!file_exists($filePath)) {
            throw new \Exception('Missing file or unable to open ' . $filePath);
        }

        $counter = 0;
        $bankTransactionDtos = [];

        $handle = fopen($filePath, 'r');
        while ($csvRow = fgetcsv($handle)) {
            $counter++;
            if ($counter === 1) {
                $output->writeln('Skipping header line');
                continue;
            }

            $bankTransactionDto = BankTransactionDto::decodeData($csvRow);

            $isValidTransaction = $this->transactionCodeCheckerService->verifyTransactionCode($bankTransactionDto->getTransactionCode());

            if($isValidTransaction)
            {
                $bankTransactionDtos[] = $bankTransactionDto;
            }
        }

        $html = $this->twig->render('pages/bank-transactions.html.twig', [
            'someVariable' => 123
        ]);

        $output->writeln($html);

        return 0;
    }
}
