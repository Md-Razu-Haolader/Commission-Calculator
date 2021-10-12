<?php

declare(strict_types=1);

namespace App\Services\Commission;

use App\Services\Commission\TransactionCommission;
use App\Facades\CommonHelper;
use App\Services\Currency;

class DepositCommission implements TransactionCommission
{

    private $commonConfig;
    public function __construct(Currency $currency)
    {
        $this->commonConfig = CommonHelper::loadConfig('common');
        $this->currency = $currency;
    }
    /**
     * calculate deposit commission rate
     *
     * @param array $transactionData
     * @return array
     */
    public function calculate(array $transactionData): array
    { 
        $commissionsData = [];
        array_walk($transactionData, function ($accTypeData, $accIndex) use (&$commissionsData) {
            array_walk($accTypeData, function ($yearWeekData, $ywIndex) use (&$commissionsData) {
                array_walk($yearWeekData, function ($userWiseData, $usrIndex) use (&$commissionsData) {
                    array_walk($userWiseData, function ($item, $index) use (&$commissionsData) {
                        $transactionAmount = $item[4];
                        $commissionAmount = ($transactionAmount * $this->commonConfig['DEPOSIT_COMMISSION_RATE']) / 100;
                        $commissionsData[$index] = CommonHelper::formatAmount($commissionAmount);
                    });
                });
            });
        });
        return $commissionsData;
    }
}
