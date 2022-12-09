<?php

declare(strict_types=1);

namespace App\Services\Commission;

use App\Services\Commission\Interfaces\Calculatable;
use App\Facades\CommonHelper;
use App\Services\Currency;

class WithdrawCommission implements Calculatable
{
    private $commonConfig;

    public function __construct(Currency $currency)
    {
        $this->commonConfig = CommonHelper::loadConfig('common');
        $this->currency = $currency;
    }
    /**
     * calculate withdraw commission
     *
     * @param array $transactionData
     * @return array
     */
    public function calculate(array $transactionData): array
    {
        $commissionsData = [];
        array_walk($transactionData, function ($accTransData, $accIndex) use (&$commissionsData) {
            if ($accIndex === $this->commonConfig['TRANSACTION_ACCOUNT_TYPE']['BUSINESS']) {
                $commissionsData += $this->calculateForBusinessAcc($accTransData);
            } elseif ($accIndex === $this->commonConfig['TRANSACTION_ACCOUNT_TYPE']['PRIVATE']) {
                $commissionsData += $this->calculateForPrivateAcc($accTransData);
            }
        });
        return $commissionsData;
    }
    /**
     * calculate business account withdraw commission
     *
     * @param array $transactionData
     * @return array
     */
    protected function calculateForBusinessAcc(array $transactionData): array
    {
        $commissionsData = [];
        array_walk($transactionData, function ($yearWeekData, $ywIndex) use (&$commissionsData) {
            array_walk($yearWeekData, function ($userWiseData, $usrIndex) use (&$commissionsData) {
                array_walk($userWiseData, function ($item, $index) use (&$commissionsData) {
                    $transactionAmount = trim($item[4]);
                    $commissionAmount = ($transactionAmount * $this->commonConfig['BUSINESS_WITHDRAWAL_COMMISSION_RATE']) / 100;
                    $commissionsData[$index] = CommonHelper::formatAmount($commissionAmount);
                });
            });
        });
        return $commissionsData;
    }
    /**
     * calculate private account withdraw commission
     *
     * @param array $transactionData
     * @return array
     */
    protected function calculateForPrivateAcc(array $transactionData): array
    {
        $commissions = [];
        array_walk($transactionData, function ($yearWeekData, $ywIndex) use (&$commissions, $transactionData) {
            array_walk($yearWeekData, function ($userWiseData, $usrIndex) use (&$commissions, $transactionData, $ywIndex) {
                $totalWeeklyTransAmount = 0;
                $weeklyTransCount = 1;
                array_walk($userWiseData, function ($item, $index) use (&$commissions, &$totalWeeklyTransAmount, &$weeklyTransCount, $transactionData, $ywIndex) {
                    // convert currency to EUR and assign to transaction array
                    $item[6] = $this->currency->convertToEur((float)$item[4], $item[5]);

                    list($transactionDate, $userId, $accType, $transactionType, $transactionAmount, $currencyType, $amountInEuro) = array_map('trim', $item);
                    $isCommissionAble = false;

                    $totalWeeklyTransAmount += $amountInEuro;

                    $commissionAmount = 0;
                    if ((isset($commissions['isCommissionAble'][$ywIndex][$userId]) && $commissions['isCommissionAble'][$ywIndex][$userId] === true)) {
                        $commissionAmount = ($amountInEuro * $this->commonConfig['PRIVATE_WITHDRAWAL_COMMISSION_RATE']) / 100;
                    } elseif ($amountInEuro > 1000) {
                        $commissionAmount = (($amountInEuro - 1000) * $this->commonConfig['PRIVATE_WITHDRAWAL_COMMISSION_RATE']) / 100;
                        $isCommissionAble = true;
                    } elseif ($totalWeeklyTransAmount > 1000) {
                        $commissionAmount = (($totalWeeklyTransAmount - 1000) * $this->commonConfig['PRIVATE_WITHDRAWAL_COMMISSION_RATE']) / 100;
                        $isCommissionAble = true;
                    } elseif ($weeklyTransCount > $this->commonConfig['TRANSACTION_LIMIT_PER_WEEK']) {
                        $commissionAmount = ($amountInEuro * $this->commonConfig['PRIVATE_WITHDRAWAL_COMMISSION_RATE']) / 100;
                        $isCommissionAble = true;
                    } else {
                        $commissions['amount'][$index] = 0;
                    }
                    // convert currency from EUR
                    $commissions['amount'][$index] = $this->currency->convertFromEur((float)$commissionAmount, $currencyType);
                    // generate isCommissionAble array flag for further calculation logic
                    if ($isCommissionAble === true) {
                        $commissions['isCommissionAble'][$ywIndex][$userId] = true;
                    }
                    $weeklyTransCount++;
                });
            });
        });
        return isset($commissions['amount']) && count($commissions['amount']) > 0 ? $commissions['amount'] : [];
    }
}
