<?php

namespace Padua\CsvImporter\Exception;

class Exception extends \Exception implements ExceptionWithContextInterface
{
    /**
     * @var mixed Any context information to be passed on.
     */
    protected $context;

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param mixed $context
     */
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, $context = null)
    {
        parent::__construct($message, $code, $previous);

        $this->context = $context;
    }

    /**
     * @param string $message
     * @param null $context
     * @param int $code
     * @param \Throwable|null $previous
     * @return static
     */
    public static function create(string $message, $context = null, int $code = 0, \Throwable $previous = null)
    {
        return new static($message, $code, $previous, $context);
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }
}
