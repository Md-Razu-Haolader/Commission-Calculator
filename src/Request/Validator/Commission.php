<?php

declare(strict_types=1);

namespace App\Request\Validator;
use App\Facades\CommonHelper;
use App\Services\Currency;

class Commission implements BaseValidator
{
    private $commonConfig;
    private $currency;
    public function __construct()
    {
        $this->commonConfig = CommonHelper::loadConfig('common');
        $this->currency = new Currency();
    }
    /**
     * check valid csv file
     *
     * @param string $filePath
     * @return array
     */
    public function validate($filePath): array
    {
        ini_set('memory_limit', '-1');
        $response = [
            'result' => false,
            'msg' => 'Please provide a valid csv file',
        ];
        if (isset($filePath) && !empty($filePath) && file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'csv') {
            $response = [
                'result' => true,
                'msg' => 'File is valid',
            ];
            $currencyRates = $this->currency->getAllCurrencyRates(); 
            if (isset($currencyRates['rates']) && count($currencyRates['rates']) > 0) {  
                $transactionItems = array_map('str_getcsv', file($filePath));
                try { 
                    array_walk($transactionItems, function ($item) use($currencyRates) {

                        $transDate = isset($item[0]) && !empty($item[0]) ? trim($item[0]) : '';
                        $userId = isset($item[1]) && !empty($item[1]) ? trim($item[1]) : '';
                        $accType = isset($item[2]) && !empty($item[2]) ? trim($item[2]) : '';
                        $transType = isset($item[3]) && !empty($item[3]) ? trim($item[3]) : '';
                        $amount = isset($item[4]) && !empty($item[4]) ? trim($item[4]) : '';
                        $currencyType = isset($item[5]) && !empty($item[5]) ? trim($item[5]) : '';
                        
                        $isValidDate = CommonHelper::isValidDate($transDate, 'Y-m-d');
                        if (!($isValidDate === true && is_numeric($userId) === true && in_array($accType, $this->commonConfig['TRANSACTION_ACCOUNT_TYPE']) === true
                            && in_array($transType, $this->commonConfig['TRANSACTION_TYPE']) === true && is_numeric($amount) === true && isset($currencyRates['rates'][$currencyType]))) {
                            throw new \Exception;
                        }
                    });
                } catch (\Exception $exception) {
                    $response = [
                        'result' => false,
                        'msg' => 'Please provide a valid csv file',
                    ];
                }
            }    
            
        }
        return $response;
    }

}