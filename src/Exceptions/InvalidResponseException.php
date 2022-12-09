<?php

declare(strict_types=1);

namespace App\Exceptions;

class InvalidResponseException extends \Exception
{
    public function __construct(string $message = 'Invalid response')
    {
        parent::__construct($message);
    }
}
