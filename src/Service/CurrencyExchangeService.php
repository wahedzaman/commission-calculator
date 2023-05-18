<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Service;

use APP\CommissionCalculator\Configs\AppConfig;

class CurrencyExchangeService
{
    private $exchangeRates = [];

    public function __construct()
    {
        $this->exchangeRates = $this->callCurrencyExchangeAPI();
    }

    public function currencyNeedsExchange(string $mainCurrency)
    {
        return $mainCurrency !== AppConfig::getBaseCurrency();
    }

    public function fromOtherToEuro(float $amount, string $currency): float
    {
        $exchangeRate = $this->getExchangeRateForCurrency($currency);

        return $amount / $exchangeRate;
    }

    public function fromEuroToOther(float $amount, string $currency): float
    {
        $exchangeRate = $this->getExchangeRateForCurrency($currency);

        return $amount * $exchangeRate;
    }

    public function callCurrencyExchangeAPI(): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, AppConfig::getCurrencyExchangeAPI());
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
        $exchangeRates = $data['rates'];

        return $exchangeRates;
    }

    private function getExchangeRateForCurrency(string $currency): float
    {
        return $this->exchangeRates[$currency];
    }
}
