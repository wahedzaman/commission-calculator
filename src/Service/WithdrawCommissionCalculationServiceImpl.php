<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Service;

use APP\CommissionCalculator\Configs\AppConfig;
use APP\CommissionCalculator\Enums\UserType;
use APP\CommissionCalculator\Interfaces\CommissionCalculationInterface;
use APP\CommissionCalculator\Model\Transaction;
use APP\CommissionCalculator\Utility\DateUtility;

class WithdrawCommissionCalculationServiceImpl implements CommissionCalculationInterface
{
    private CurrencyExchangeService $currencyExchangeService;
    private DateUtility $dateUtility;
    private $transactionRecords = [];

    public function __construct()
    {
        $this->currencyExchangeService = new CurrencyExchangeService();
        $this->dateUtility = new DateUtility();
    }

    public function calculateCommission(Transaction $transaction): float
    {
        $euroValue = $transaction->operation_amount;
        if ($this->currencyExchangeService->currencyNeedsExchange($transaction->operation_currency)) {
            $euroValue = $this->currencyExchangeService->fromOtherToEuro($transaction->operation_amount, $transaction->operation_currency);
        }

        if ($transaction->user_type === UserType::private->name) {
            if (isset($this->transactionRecords[$transaction->user_id]) && $this->dateUtility->isFromSameWeek($transaction->operation_date, $this->transactionRecords[$transaction->user_id]['last_txn_date'])) {
                $this->transactionRecords[$transaction->user_id]['last_txn_date'] = $transaction->operation_date;
                $this->transactionRecords[$transaction->user_id]['txn_count']++;
                $this->transactionRecords[$transaction->user_id]['last_amount'] = $euroValue;
            } else {
                $this->transactionRecords[$transaction->user_id] = ['last_txn_date' => $transaction->operation_date, 'txn_count' => 1, 'total_amount' => 0, 'last_amount' => $euroValue];
            }
            $commission = $this->getCommissionableAmount($this->transactionRecords[$transaction->user_id]);
            if ($this->currencyExchangeService->currencyNeedsExchange($transaction->operation_currency)) {
                $commission = $this->currencyExchangeService->fromEuroToOther($commission, $transaction->operation_currency);
            }
            return $commission;
        } else {
            return $euroValue * (AppConfig::getWithdrawFeeForPrivateClient() / 100);
        }
    }

    public function getCommissionableAmount(array &$transactionRecord): float
    {
        $commissionableAmount = 0;
        if ($transactionRecord['txn_count'] > AppConfig::getWithDrawFeeCountLimitForPrivateClient()) {
            $commissionableAmount = $transactionRecord['last_amount'];
        } else {
            if ($transactionRecord['total_amount'] > 0) {
                if ($transactionRecord['total_amount'] > AppConfig::getWithDrawFeeAmountLimitForPrivateClient()) {
                    $commissionableAmount = $transactionRecord['last_amount'];
                } else {
                    $commissionableAmount = $transactionRecord['last_amount'] - (AppConfig::getWithDrawFeeAmountLimitForPrivateClient() - $transactionRecord['total_amount']);
                }
            } else {
                if ($transactionRecord['last_amount'] > AppConfig::getWithDrawFeeAmountLimitForPrivateClient()) {
                    $commissionableAmount = $transactionRecord['last_amount'] - AppConfig::getWithDrawFeeAmountLimitForPrivateClient();
                }
            }
        }
        $transactionRecord['total_amount'] += $transactionRecord['last_amount'];

        return $commissionableAmount * (AppConfig::getWithdrawFeeForBusinessClient() / 100);
    }
}
