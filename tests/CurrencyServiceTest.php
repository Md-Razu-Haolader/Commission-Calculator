<?php

declare(strict_types=1);

use App\Services\Currency;
use PHPUnit\Framework\TestCase;

final class CurrencyServiceTest extends TestCase
{

    private static $currencyService;

    public static function setUpBeforeClass(): void
    {
        static::$currencyService = new Currency();
    }

    public function testGetCurrencyRatesShouldReturnArray()
    {
        $res = static::$currencyService->getAllCurrencyRates();
        $result = is_array($res) && count($res) ? true : false;
        $this->assertTrue($result);
    }
}
