<?php

namespace Padua\CsvImporter\Exception;

interface ExceptionWithContextInterface
{
    public function getContext();
}
