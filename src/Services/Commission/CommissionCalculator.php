<?php

declare(strict_types=1);

namespace App\Services\Commission;

use App\Repositories\DataRepository;
use App\Factories\TransactionFactory;
use App\Request\Validator\CommissionValidator;

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
                // calculation logic for deposit and withdrawal
                $transFactory = $transactionFactory->create($transactionType);
                $commissionData = $commissionData + $transFactory->calculate($item);
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
    public function calculate(): void
    {
        global $argv;
        $filePath = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : '';
        (new CommissionValidator())->validate($filePath);
        $commissionData = $this->processCommission();
        if (count($commissionData) > 0) {
            echo implode("\n", $commissionData);
        }
    }
}
