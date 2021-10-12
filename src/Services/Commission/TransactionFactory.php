<?php

declare(strict_types=1);

namespace App\Services\Commission;

use App\Facades\CommonHelper;
use App\Services\Currency;
use App\Services\Commission\DepositCommission;
use App\Services\Commission\WithdrawCommission;

class TransactionFactory
{
    private $commonConfig;
    private $currency;

    public function __construct()
    {
        $this->commonConfig = CommonHelper::loadConfig('common');
        $this->currency = new Currency();
    }
    /**
     * process transaction by it's type
     *
     * @param string $transactionType
     * @return TransactionCommission
     */
    public function init(string $transactionType): TransactionCommission
    {
        switch ($transactionType) {
            case $this->commonConfig['TRANSACTION_TYPE']['DEPOSIT']:
                return new DepositCommission($this->currency);
            case $this->commonConfig['TRANSACTION_TYPE']['WITHDRAW']:
                return new WithdrawCommission($this->currency);
            default:
                throw new \Exception("Unknown transaction: " . $transactionType);
                break;
        }
    }
}
