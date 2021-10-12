<?php

declare(strict_types=1);

namespace App\Services;

use App\Facades\CommonHelper;
use App\Request\Http\CurlHandler;

class Currency
{
    private $commonConfig;
    private $curlHandler;
    private $currencyRates;
    
    public function __construct()
    {
        $this->commonConfig = CommonHelper::loadConfig('common');
        $this->curlHandler = new CurlHandler();
    }

    /**
     * get currency from exchangeratesapi api
     *
     * @return array
     */
    public function getAllCurrencyRates(): array
    {
        $rates = [];
        if (
            isset($this->commonConfig['EXCHANGE_RATE_API_URL'])
            && !empty(isset($this->commonConfig['EXCHANGE_RATE_API_URL']))
            && isset($this->commonConfig['EXCHANGE_RATE_API_ACCESS_KEY'])
            && !empty($this->commonConfig['EXCHANGE_RATE_API_ACCESS_KEY'])
        ) {
            $apiUrl = $this->commonConfig['EXCHANGE_RATE_API_URL'] . 'v1/latest?access_key=' . $this->commonConfig['EXCHANGE_RATE_API_ACCESS_KEY'];
            $response = $this->curlHandler->getRequest($apiUrl);
            $result = json_decode($response, true);

            if (isset($result['success']) && $result['success'] === true && isset($result['rates']) && count($result['rates']) > 0) {
                $rates = $result;
            }
        }
        return $this->currencyRates = $rates;
    }

    /**
     * convert currency from EURO
     *
     * @param float $amount
     * @param string $toCurrencyName
     * @return float
     */
    public function convertFromEur(float $amount, string $toCurrencyName): float
    {
        $currencyAmount = 0;
        if ($toCurrencyName === 'EUR') {
            return CommonHelper::formatAmount($amount);
        }
        $currencyRates = is_array($this->currencyRates) && count($this->currencyRates) > 0 ? $this->currencyRates : $this->getAllCurrencyRates();
        if (isset($currencyRates['rates']) && count($currencyRates['rates']) > 0) {
            $currencyAmount = $amount * $currencyRates['rates'][$toCurrencyName];
        }

        return CommonHelper::formatAmount($currencyAmount);
    }
    /**
     * convert currency to EURO
     *
     * @param float $amount
     * @param string $fromCurrencyName
     * @return float
     */
    public function convertToEur(float $amount, string $fromCurrencyName): float
    {
        if ($fromCurrencyName === 'EUR') {
            return CommonHelper::formatAmount($amount);
        }
        $currencyAmount = 0;
        $currencyRates = is_array($this->currencyRates) && count($this->currencyRates) > 0 ? $this->currencyRates : $this->getAllCurrencyRates();
        if (isset($currencyRates['rates']) && count($currencyRates['rates']) > 0) {
            $currencyAmount = $amount / $currencyRates['rates'][$fromCurrencyName];
        }

        return CommonHelper::formatAmount($currencyAmount);
    }
}
