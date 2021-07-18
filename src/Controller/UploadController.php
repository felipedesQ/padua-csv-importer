<?php

namespace Padua\CsvImporter\Controller;

use Padua\CsvImporter\Outputter\BankTransactionsOutputter;
use Padua\CsvImporter\Service\CsvParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Padua\CsvImporter\Service\FileUploader;

class UploadController extends AbstractController
{
    /**
     * @var BankTransactionsOutputter
     */
    private $bankTransactionsOutputter;

    /**
     * @var CsvParser
     */
    private $csvParser;

    public function __construct(
        CsvParser $csvParser,
        BankTransactionsOutputter $bankTransactionsOutputter
    )
    {
        $this->bankTransactionsOutputter = $bankTransactionsOutputter;
        $this->csvParser = $csvParser;
    }

    /**
     * @Route("/doUpload", name="do-upload")
     * @param Request $request
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param LoggerInterface $logger
     * @return Response
     */
    public function index(Request $request,
                          string $uploadDir,
                          FileUploader $uploader,
                          LoggerInterface $logger): Response
    {
        $token = $request->get("token");

        if (!$this->isCsrfTokenValid('upload', $token)) {
            $logger->info("CSRF failure");

            return new Response("Operation not allowed", Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);
        }

        $file = $request->files->get('myfile');

        if (empty($file)) {
            return new Response("No file specified",
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }

        $filename = $file->getClientOriginalName();
        $uploader->upload($uploadDir, $file, $filename);

        $filePath = $uploadDir . '/' . $filename;
        $bankTransactionDtos = [];
        $bankTransactionDtos = $this->csvParser->parseCsv($filePath);

        //generate the display table for the valid transactions
        $transactionDetails = $this->bankTransactionsOutputter->toArray($bankTransactionDtos);

        //generate html output
        $htmlOutput = $this->bankTransactionsOutputter->toHTML($transactionDetails);

        return new Response($htmlOutput);
    }
}