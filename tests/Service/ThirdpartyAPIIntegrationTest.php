<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Tests\Service;


use APP\CommissionCalculator\Service\CurrencyExchangeService;
use PHPUnit\Framework\TestCase;

class ThirdpartyAPIIntegrationTest extends TestCase
{

    public function test_exchange_api_is_working()
    {
        $exchangeAPI = new CurrencyExchangeService();
        $exchangeRates = $exchangeAPI->callCurrencyExchangeAPI();
        $this->assertIsArray($exchangeRates);
    }
}
