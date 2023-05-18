<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Interfaces;

use APP\CommissionCalculator\Model\Transaction;

interface CommissionCalculationInterface
{
    public function calculateCommission(Transaction $transaction): float;
}
