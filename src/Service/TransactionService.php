<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Service;

use APP\CommissionCalculator\Enums\TransactionType;
use APP\CommissionCalculator\Model\Transaction;

class TransactionService
{
    private DepositCommissionCalculationServiceImpl $depositCommissionCalculationService;
    private WithdrawCommissionCalculationServiceImpl $withdrawCommissionCalculationService;

    public function __construct()
    {
        $this->depositCommissionCalculationService = new DepositCommissionCalculationServiceImpl();
        $this->withdrawCommissionCalculationService = new WithdrawCommissionCalculationServiceImpl();
    }

    public function processTransactions(array $transactions)
    {
        for ($index = 0; $index < count($transactions); ++$index) {
            $transaction = $transactions[$index];
            $commission = $this->processSingleTransaction($transaction);
            $commissions[] = number_format($commission, 2);
            echo "$commissions[$index]\n";
        }
    }

    public function processSingleTransaction(Transaction $transaction): float
    {
        if ($transaction->operation_type === TransactionType::deposit->name) {
            return $this->depositCommissionCalculationService->calculateCommission($transaction);
        } else {
            return $this->withdrawCommissionCalculationService->calculateCommission($transaction);
        }
    }
}
