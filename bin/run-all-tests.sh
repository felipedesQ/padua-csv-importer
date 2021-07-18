#!/usr/bin/env bash

cd /padua-csv-importer

echo "Clearing log..."
> var/log/test.log

echo "Rebuilding cache..."
bin/console cache:clear --no-warmup --env=test
bin/console cache:warmup --env=test

echo "Running specifications tests..."
bin/phpspec run
echo "Running unit tests..."
bin/phpunit
