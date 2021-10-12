<?php

declare(strict_types=1);

namespace App\Request\Http;

class CurlHandler
{
    /**
     * get curl request
     *
     * @param string $url
     * @param array $header
     * @return void
     */
    public function getRequest(string $url, array $header = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
