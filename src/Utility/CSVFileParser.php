<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Utility;

class CSVFileParser
{
    public function __construct()
    {
    }

    public function isValidCSVFile(string $fileName): bool
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        return strtolower($extension) === 'csv';
    }

    public function canProcessCSVFile($fileName): bool
    {
        return is_readable($fileName);
    }

    public function parse(string $fileName): array
    {
        $csvLines = [];
        $csvFile = fopen($fileName, 'r');
        while (($line = fgetcsv($csvFile)) !== false) {
            $csvLines[] = $line;
        }
        fclose($csvFile);

        return $csvLines;
    }
}
