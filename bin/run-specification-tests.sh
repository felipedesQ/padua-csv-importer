#!/usr/bin/env bash

cd /padua-csv-importer

echo "Running specifications tests..."
php bin/phpspec run
