<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Repositories\TransactionRepository;
use App\Services\Commission\CommissionCalculator;

final class CommissionServiceTest extends TestCase
{

    private static $commissionCalculator;

    public static function setUpBeforeClass(): void
    {
        global $argv;
        $argv[1] = 'input.csv';
        static::$commissionCalculator = new CommissionCalculator(new TransactionRepository);
    }

    public function testShouldCalculateComissionWhenValidDataGiven()
    {
        ob_start();
        static::$commissionCalculator->calculate();
        $output = ob_get_clean();
        $result = strpos($output, "\n");
        $this->assertNotEquals(false, $result, $output);
    }

    public function testProcessCommissionShouldReturnArray()
    {
        $res = static::$commissionCalculator->processCommission();
        $result = is_array($res) && count($res) ? true : false;
        $this->assertTrue($result);
    }
}
