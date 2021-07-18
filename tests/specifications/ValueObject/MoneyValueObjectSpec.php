<?php

namespace specifications\Padua\CsvImporter\ValueObject;

use Padua\CsvImporter\ValueObject\MoneyValueObject;
use PhpSpec\ObjectBehavior;

class MoneyValueObjectSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MoneyValueObject::class);
    }

    function it_works_from_int()
    {
        $this->beConstructedThrough('fromFloat', [12]);
        $this->toFloat()->shouldBe(12.00);
    }
}
