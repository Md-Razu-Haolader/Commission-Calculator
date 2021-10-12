<?php

declare(strict_types=1);

namespace App\Request\Validator;

interface BaseValidator
{
    public function validate($requestData): array;
}