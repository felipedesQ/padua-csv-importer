<?php

namespace Padua\ValueObject\UnitTests\Service;

use Padua\CsvImporter\Entity\BankTransactionEntity;
use Padua\CsvImporter\Service\CheckCharacterService;
use Padua\CsvImporter\Service\TransactionCodeCheckerService;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TransactionCodeCheckerServiceTest extends KernelTestCase
{

    /**
     * @var TransactionCodeCheckerService
     */
    private $transactionCodeCheckerService;

    public function setUp(): void
    {
        self::bootKernel();
        $this->transactionCodeCheckerService = static::$kernel->getContainer()->get('test.transaction_code_checker_service');
    }

    public function testVerifyTransactionCode()
    {
        $testValue = 'somevalue';
        $expected = false;
        $isValidTransaction = $this->transactionCodeCheckerService->verifyTransactionCode($testValue);
        $this->assertEquals($expected, $isValidTransaction);


        $testValue = 'S98EBHDWG3';
        $expected = true;
        $isValidTransaction = $this->transactionCodeCheckerService->verifyTransactionCode($testValue);
        $this->assertEquals($expected, $isValidTransaction);

    }
}
