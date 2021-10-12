<?php

declare(strict_types=1);

use App\Helpers\Common;
use PHPUnit\Framework\TestCase;

final class CommonHelperTest extends TestCase
{

    private $commonHelper;

    public function setUp()
    {
        $this->commonHelper = new Common();
    }

    public function testValidDateString()
    {
        $dateString = '2021-05-28';
        $format = 'Y-m-d';
        $result = $this->commonHelper->isValidDate($dateString, $format);
        $this->assertTrue($result);
    }
    public function testFormatAmountMustReturnFloat()
    {
        $amount = 8.66;
        $res = $this->commonHelper->formatAmount($amount);
        $result = is_float($res) ? true : false;
        $this->assertTrue($result);
    }
    public function testLoadConfigWithWrongConfigName()
    {
        $configName = 'test';
        $res = $this->commonHelper->loadConfig($configName);
        $result = count($res) > 0 ? true : false;
        $this->assertFalse($result);
    }
    public function testLoadConfigMustReturnArray()
    {
        $configName = 'common';
        $res = $this->commonHelper->loadConfig($configName); 
        $result = count($res) > 0 ? true : false;
        $this->assertTrue($result);
    }
}
