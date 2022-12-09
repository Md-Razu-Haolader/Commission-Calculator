<?php

declare(strict_types=1);

namespace App\Factories;

use App\Facades\CommonHelper;
use App\Services\Currency;
use App\Services\Commission\Interfaces\Calculatable;
use App\Services\Commission\DepositCommission;
use App\Services\Commission\WithdrawCommission;
use App\Exceptions\ResourceNotFoundException;

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
     * @return Calculatable
     */
    public function create(string $transactionType): Calculatable
    {
        switch ($transactionType) {
            case $this->commonConfig['TRANSACTION_TYPE']['DEPOSIT']:
                return new DepositCommission($this->currency);
            case $this->commonConfig['TRANSACTION_TYPE']['WITHDRAW']:
                return new WithdrawCommission($this->currency);
            default:
                throw new ResourceNotFoundException("{$transactionType} Transaction not found");
                break;
        }
    }
}
