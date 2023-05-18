<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Utility;

use APP\CommissionCalculator\Model\Transaction;

class CSVToTransactionHelper
{
    public function convertCSVLineArrayToTranscations(array $csvLines): array
    {
        // todo:: try catch to handle broken CSV
        foreach ($csvLines as $line) {
            list($operation_date, $user_id, $user_type, $operation_type, $operation_amount, $operation_currency) = $line;
            $transaction = new Transaction($operation_date, (int) $user_id, $user_type, $operation_type, (float) $operation_amount, $operation_currency);
            $transactions[] = $transaction;
        }

        return $transactions;
    }
}
