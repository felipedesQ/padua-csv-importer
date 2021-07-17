<?php

namespace Padua\CsvImporter\Command;

use Symfony\Component\Console\Command\Command;

class ImportCsvCommand extends Command
{
    const FILE_NAME = 'fileName';

    /**
     * @var string
     */
    private $uploadLocation;

    public function __construct(
        string $uploadLocation
    )
    {
        parent::__construct();

        $this->uploadLocation = $uploadLocation;
    }
}
