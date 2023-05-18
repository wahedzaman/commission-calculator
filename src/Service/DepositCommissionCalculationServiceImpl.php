<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Service;

use APP\CommissionCalculator\Configs\AppConfig;
use APP\CommissionCalculator\Interfaces\CommissionCalculationInterface;
use APP\CommissionCalculator\Model\Transaction;

class DepositCommissionCalculationServiceImpl implements CommissionCalculationInterface
{
    public function calculateCommission(Transaction $transaction): float
    {
        return $transaction->operation_amount * (AppConfig::getDepositFee() / 100);
    }
}
