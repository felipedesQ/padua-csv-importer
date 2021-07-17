<?php

namespace Padua\CsvImporter\ValueObject;

use Padua\CsvImporter\Exception\ValidationException;

class DateTimeValueObject
{
    /**
     * @throws ValidationException
     */
    public static function fromString($value) :self
    {
        if (!is_string($value)) {
            throw new ValidationException('Expected string value');
        }
        $object = new static;
        $object->value = $value;

        $object->validate();

        return $object;
    }

    public function toDateTime(): \DateTime
    {
        $dateTime = new \DateTime($this->value);
        $dateTime->format("d/m/Y g:i A");

        return $dateTime;
    }

    protected function validate()
    {
        if (false === strtotime($this->value)) {
            throw new ValidationException(
                sprintf(
                    'Expected a valid relative format string, got %s',
                    $this->value
                )
            );
        }
    }
}
