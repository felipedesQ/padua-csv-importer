<?php

namespace Padua\CsvImporter\ValueObject;

use Padua\CsvImporter\Exception\ValidationException;

class MoneyValueObject
{
    /**
     * @var float
     */
    protected $value;

    /**
     * @throws ValidationException
     */
    public static function fromFloat($value): self
    {
        if (is_numeric($value)) {
            $value = (float)$value;
        }
        if (!is_float($value)) {
            throw new ValidationException('Expected float value');
        }
        $object = new static;
        $object->value = $object->format($value);
        return $object;
    }

    private function format(float $amount): float
    {
        return round($amount, 2);
    }

    public function toFloat(): float
    {
        return $this->value;
    }

    public function toMoney(): string
    {
        $moneyFormatter = new \NumberFormatter('en_AU', \NumberFormatter::CURRENCY);
        return $moneyFormatter->formatCurrency($this->value, 'AUD');
    }

    protected function validate(): void
    {
        //TODO: add money/currency validation
    }
}
