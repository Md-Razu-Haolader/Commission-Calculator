<?php

namespace App\Repositories;

interface DataRepository
{
    public function getAllData(): array;
    public function getWeekWiseTransactionData(): array;
}
