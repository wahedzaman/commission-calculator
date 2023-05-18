<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Configs;

class AppConfig
{
    private static $config = [
        /*  changing base currency will have no effect but resulting in miscalculate commissions
            unless the currencyExchange API also reflects the same base currency and exchange rates
        */

        'base_currency' => 'EUR',

        'withdraw_free_count_limit' => 3,

        'withdraw_free_amount_limit' => 1000,

        'deposit_fee' => 0.03,

        'withdraw_fee_business' => 0.5,

        'withdraw_fee_private' => 0.3,

        'currency_exchange_api' => 'https://aflion.com/exchange-rate.php',
    ];

    public static function getBaseCurrency(): string
    {
        return static::$config['base_currency'];
    }

    public static function getWithDrawFeeCountLimitForPrivateClient(): int
    {
        return self::$config['withdraw_free_count_limit'];
    }

    public static function getWithDrawFeeAmountLimitForPrivateClient(): int
    {
        return self::$config['withdraw_free_amount_limit'];
    }

    public static function getDepositFee(): float
    {
        return self::$config['deposit_fee'];
    }

    public static function getWithdrawFeeForPrivateClient(): float
    {
        return self::$config['withdraw_fee_business'];
    }

    public static function getWithdrawFeeForBusinessClient(): float
    {
        return self::$config['withdraw_fee_private'];
    }

    public static function getCurrencyExchangeAPI(): string
    {
        return self::$config['currency_exchange_api'];
    }
}
