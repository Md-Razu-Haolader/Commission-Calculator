<?php

declare(strict_types=1);

namespace App\Services\Commission;

use App\Repositories\DataRepository;
use App\Services\Commission\TransactionFactory;
use App\Request\Validator\BaseValidator;

class CommissionCalculator
{
    public function __construct(DataRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * process commision calculation data
     *
     * @return array
     */
    public function processCommission(): array
    { 
        $commissionData = [];
        $weekWiseTransactionData = $this->repository->getWeekWiseTransactionData();
        if (count($weekWiseTransactionData) > 0) {
            $transactionFactory = new TransactionFactory();
            array_walk($weekWiseTransactionData, function ($item, $transactionType) use (&$commissionData, $transactionFactory) {
                try { 
                    // calculation logic for deposit and withdrawal
                    $transaction = $transactionFactory->init($transactionType);
                    $commissionData = $commissionData + $transaction->calculate($item);
                } catch (\Exception $exception) {
                    
                }
                
            });
        }
        ksort($commissionData);
        return $commissionData;
    }

    /**
     * calculate commission from csv file
     *
     * @return void
     */
    public function calculate(BaseValidator $commissionValidator): void
    {
        global $argv;
        $filePath = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : '';
        $csvValidate = $commissionValidator->validate($filePath);
        if ($csvValidate['result'] === true) {
            $commissionData = $this->processCommission();
            if (count($commissionData) > 0) {
                echo implode("\n", $commissionData);
            }
        } else {
            echo $csvValidate['msg'];
        }
    }
}
