<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Repositories\TransactionRepository;
use App\Services\Commission\CommissionCalculator;
use App\Request\Validator\Commission;

final class CommissionTest extends TestCase
{

    private $commissionCalculator;
    private $commissionValidator;

    public function setUp()
    {
        global $argv;
        $argv[1] = 'input.csv';
        $this->commissionCalculator = new CommissionCalculator(new TransactionRepository);
        $this->commissionValidator = new Commission();
    }

    public function testCalculate()
    {
        ob_start();
        $this->commissionCalculator->calculate($this->commissionValidator);
        $output = ob_get_clean();
        $result = strpos($output, "\n");
        $this->assertNotEquals(false, $result, $output);
    }

    public function testProcessCommissionReturnArray()
    {
        $res = $this->commissionCalculator->processCommission();
        $result = is_array($res) && count($res) ? true : false;
        $this->assertTrue($result);
    }
    public function testCommissionValidate()
    {
        $fileName = 'input.csv';
        $validate = $this->commissionValidator->validate($fileName);
        $this->assertTrue($validate['result']);
    }
}
