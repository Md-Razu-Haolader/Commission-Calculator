<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Helpers\Common;

final class CommonHelperTest extends TestCase
{

    private static $commonHelper;

    public static function setUpBeforeClass(): void
    {
        static::$commonHelper = new Common();
    }
    protected function setUp(): void
    {
    }

    public function testIsValidDateShouldReturnTrueWhenValidDateStringGiven()
    {
        $dateString = '2021-05-28';
        $format = 'Y-m-d';
        $result = static::$commonHelper->isValidDate($dateString, $format);
        $this->assertTrue($result);
    }
    public function testFormatAmountMustReturnFloatWhenDecimalAmountGiven()
    {
        $amount = 8.66;
        $res = static::$commonHelper->formatAmount($amount);
        $result = is_float($res) ? true : false;
        $this->assertTrue($result);
    }
    public function testShouldNotLoadConfigFileWhenWrongFileGiven()
    {
        $configName = 'test';
        $res = static::$commonHelper->loadConfig($configName);
        $result = count($res) > 0 ? true : false;
        $this->assertFalse($result);
    }
    public function testLoadConfigMustReturnArray()
    {
        $configName = 'common';
        $res = static::$commonHelper->loadConfig($configName);
        $result = count($res) > 0 ? true : false;
        $this->assertTrue($result);
    }
}
