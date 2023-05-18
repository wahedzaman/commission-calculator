<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use APP\CommissionCalculator\Service\TransactionService;
use APP\CommissionCalculator\Utility\CSVFileParser;
use APP\CommissionCalculator\Utility\CSVToTransactionHelper;

if (isset($argv[1])) {
    $csvFileParser = new CSVFileParser();
    $csvToTransactionHelper = new CSVToTransactionHelper();
    $transactionService = new TransactionService();

    $fileName = $argv[1];
    if ($csvFileParser->isValidCSVFile($fileName)) {
        if ($csvFileParser->canProcessCSVFile($fileName)) {
            $csvLines = $csvFileParser->parse($fileName);
            $transactions = $csvToTransactionHelper->convertCSVLineArrayToTranscations($csvLines);
            $transactionService->processTransactions($transactions);
        } else {
            echo 'provided file could not be processed';
        }
    } else {
        echo 'only csv file type is accepted';
    }
} else {
    echo "csv file is not provided. Please provide a valid csv file \n";
    echo 'run > php index.php input.csv';
}
