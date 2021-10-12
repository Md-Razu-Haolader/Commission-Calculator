<?php

declare(strict_types=1);

use App\Services\Currency;
use PHPUnit\Framework\TestCase;

final class CurrencyTest extends TestCase
{

    private $currencyService;

    public function setUp()
    {
        $this->currencyService = new Currency();
    }

    public function testGetCurrencyRatesReturnArray()
    {
        $res = $this->currencyService->getAllCurrencyRates();
        $result = is_array($res) && count($res) ? true : false;
        $this->assertTrue($result);
    }
    
}
