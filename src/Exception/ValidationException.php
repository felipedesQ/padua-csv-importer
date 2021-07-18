<?php

namespace Padua\CsvImporter\Exception;

class ValidationException extends Exception
{
    /**
     * Override parent function for the sole purpose of enforcing the return type so the IDE doesn't get mixed up.
     *
     * @param string $message
     * @param null $context
     * @param int $code
     * @param \Throwable|null $previous
     * @return ValidationException
     */
    public static function create(string $message, $context = null, int $code = 0, \Throwable $previous = null): ValidationException
    {
        return parent::create($message, $context, $code, $previous);
    }
}
