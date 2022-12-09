<?php

declare(strict_types=1);

namespace App\Exceptions;

class ValidationException extends \Exception
{
    public function __construct(string $message = 'Invalid data provided.')
    {
        parent::__construct($message);
    }

    public static function withErrors(array $errors, string $message = 'Invalid data provided.'): ValidationException
    {
        $validationError = new ValidationException($message);
        $validationError->errors = $errors;

        return $validationError;
    }
}
