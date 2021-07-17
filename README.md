# padua-csv-importer
CSV Object Importer

Clone the repository in your local machine.

###### git clone https://github.com/felipedesQ/padua-csv-importer.git

Run the composer update/install

###### php -d memory_limit=-1 /usr/local/bin/composer install
###### php -d memory_limit=-1 /usr/local/bin/composer update

To read a CSV file, run the command **padua:csv:import**

Example:

_A sample CSV is already in var/upload directory_

###### bin/console padua:csv:import "BankTransactions.csv"

