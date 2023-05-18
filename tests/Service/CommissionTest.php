<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Tests\Service;

use APP\CommissionCalculator\Model\Transaction;
use APP\CommissionCalculator\Service\DepositCommissionCalculationServiceImpl;
use APP\CommissionCalculator\Service\WithdrawCommissionCalculationServiceImpl;
use PHPUnit\Framework\TestCase;

class CommissionTest extends TestCase
{
    public function test_transaction_withdraw_commission_for_business()
    {
        $withdrawCommissionCalculationService = new WithdrawCommissionCalculationServiceImpl();
        $transaction = new Transaction('2016-01-06', 2, 'business', 'withdraw', 300.00, 'EUR');
        $commission = $withdrawCommissionCalculationService->calculateCommission($transaction);
        $this->assertSame(1.50, $commission);
    }

    public function test_transaction_withdraw_commission_for_client_free()
    {
        $withdrawCommissionCalculationService = new WithdrawCommissionCalculationServiceImpl();
        $transaction = new Transaction('2016-02-15', 1, 'private', 'withdraw', 300.00, 'EUR');
        $commission = $withdrawCommissionCalculationService->calculateCommission($transaction);
        $this->assertSame(0.0, $commission);
    }

    public function test_transaction_withdraw_commission_for_client()
    {
        $withdrawCommissionCalculationService = new WithdrawCommissionCalculationServiceImpl();
        $transaction = new Transaction('2014-12-31', 4, 'private', 'withdraw', 1200.00, 'EUR');
        $commission = $withdrawCommissionCalculationService->calculateCommission($transaction);
        $this->assertSame(0.60, $commission);
    }

    public function test_transaction_deposit_commission()
    {
        $depositCommissionCalculationService = new DepositCommissionCalculationServiceImpl();
        $transaction = new Transaction('2016-01-05', 1, 'private', 'deposit', 200.00, 'EUR');
        $commission = $depositCommissionCalculationService->calculateCommission($transaction);
        $this->assertSame(0.06, $commission);
    }
}
