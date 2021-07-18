<?php

namespace Padua\CsvImporter\ValueObject;

use Padua\CsvImporter\Exception\Exception;
use Padua\CsvImporter\Exception\ValidationException;

class StringValueObject
{
    /**
     * @throws ValidationException
     */
    public static function fromString($value): self
    {
        if (!is_string($value)) {
            throw new ValidationException('Expected string value');
        }
        $object = new static;
        $object->value = $value;

        $object->validate();

        return $object;
    }

    public function toString(): string
    {
        return $this->value;
    }

    protected function validate(): void
    {
        //TODO: add string validation
    }
}