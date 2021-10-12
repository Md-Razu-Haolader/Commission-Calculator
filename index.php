<?php

if ( file_exists( 'vendor/autoload.php' ) ) {
    require 'vendor/autoload.php';
}

use App\Repositories\TransactionRepository;
use App\Services\Commission\CommissionCalculator;
use App\Request\Validator\Commission;

$calculator = new CommissionCalculator(new TransactionRepository);
$results = $calculator->calculate(new Commission);