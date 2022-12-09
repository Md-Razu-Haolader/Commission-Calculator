<?php

declare(strict_types=1);

namespace App\Services\Commission\Interfaces;

interface Calculatable
{
    public function calculate(array $transactionData): array;
}
