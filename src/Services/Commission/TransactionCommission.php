<?php

declare(strict_types=1);

namespace App\Services\Commission;

interface TransactionCommission
{
    public function calculate(array $transactionData): array;
}
