<?php

declare(strict_types=1);

namespace App\Helpers;

class Common
{
    /**
     * load config file
     *
     * @param string $configName
     * @return array
     */
    public function loadConfig(string $configName): array
    {

        if (file_exists('src/config/' . $configName . '.php')) {
            return require 'src/config/' . $configName . '.php';
        }
    }

    /**
     * format decimal points
     *
     * @param float $amount
     * @return float
     */
    public function formatAmount(float $amount): float
    {
        return (float)number_format($amount, 2, '.', '');
    }

    /**
     * check date string is valid
     *
     * @param string $date
     * @param string $format
     * @return boolean
     */
    public function isValidDate(string $date, string $format = 'Y-m-d H:i:s'): bool
    {
        $dateObj = \DateTime::createFromFormat($format, $date);
        return $dateObj && $dateObj->format($format) == $date;
    }
}
