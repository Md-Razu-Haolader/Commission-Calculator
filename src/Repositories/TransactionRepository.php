<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\DataRepository;

class TransactionRepository implements DataRepository
{
    /**
     * get all data from csv file
     *
     * @return array
     */
    public function getAllData(): array
    {
        ini_set('memory_limit', '-1');
        global $argv;
        $data = [];
        $filePath = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : [];
        if (isset($filePath) && !empty($filePath) && file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'csv') {
            $data = array_map('str_getcsv', file($filePath));
        }
        return $data;    
    }
    /**
     * get year-week wise transaction data from csv file
     *
     * @return array
     */
    public function getWeekWiseTransactionData(): array
    {
        $transactionItems = $this->getAllData();
        $weeklyTransactionData = [];
        array_walk($transactionItems, function ($item, $index) use (&$weeklyTransactionData) {
            $transactionItem = $item;
            // get year week from Y-m-d string
            $yearWeek = date("oW", strtotime(trim($transactionItem[0])));
            list($transactionDate, $userId, $accType, $transactionType, $transactionAmount, $currencyType) = array_map('trim', $transactionItem);
            // generate weekly based transaction array
            $weeklyTransactionData[$transactionType][$accType][$yearWeek][$userId][$index] = [$transactionDate, $userId, $accType, $transactionType, $transactionAmount, $currencyType];
                
        });    
        return $weeklyTransactionData;
    }
}
