<?php

declare(strict_types=1);

namespace App\Request\Validator;

interface Validatable
{
    public function validate($requestData): void;
}
