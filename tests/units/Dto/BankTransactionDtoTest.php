<?php

namespace Padua\ValueObject\UnitTests\Dto;

use Padua\CsvImporter\Dto\BankTransactionDto;
use PHPUnit\Framework\TestCase;

class BankTransactionDtoTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_be_initialisable()
    {
        $expectedValues = [];
        $expectedValues[] = '2016-11-23 10:34PM';
        $expectedValues[] = 'S98EBHDWG3';
        $expectedValues[] = '3423';
        $expectedValues[] = 'Wages';
        $expectedValues[] = '198700';

        $bankTransactionDto = BankTransactionDto::decodeData($expectedValues);

        $this->assertEquals($expectedValues[1], $bankTransactionDto->getTransactionCode());
        $this->assertEquals($expectedValues[2], $bankTransactionDto->getCustomerNumber());
        $this->assertEquals($expectedValues[3], $bankTransactionDto->getReference());
        $this->assertEquals($expectedValues[4], $bankTransactionDto->getAmount());
    }
}
