<?php

namespace Padua\CsvImporter\ValueObject;

use Padua\CsvImporter\Exception\Exception;
use Padua\CsvImporter\Exception\ValidationException;

class IntegerValueObject
{
    /**
     * @var int
     */
    private $value;

    /**
     * @throws ValidationException
     */
    public static function fromInteger($value): self
    {
        if (!is_numeric($value)) {
            throw new ValidationException('Expected integer value');
        }
        $object = new static;
        $object->value = (int)$value;

        $object->validate();

        return $object;
    }

    public function toInteger(): int
    {
        return $this->value;
    }

    protected function validate(): void
    {
    }
}