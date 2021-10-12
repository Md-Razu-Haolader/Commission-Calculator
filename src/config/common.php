<?php
return [
    'EXCHANGE_RATE_API_URL' => 'http://api.exchangeratesapi.io/',
    'EXCHANGE_RATE_API_ACCESS_KEY' => '79a2090f78a2601cfd99d7114386d5aa',
    'DEPOSIT_COMMISSION_RATE' => '0.03',
    'BUSINESS_WITHDRAWAL_COMMISSION_RATE' => '0.5',
    'PRIVATE_WITHDRAWAL_COMMISSION_RATE' => '0.3',
    'TRANSACTION_LIMIT_PER_WEEK' => 3,
    'TRANSACTION_TYPE' => [
        'DEPOSIT' => 'deposit',
        'WITHDRAW' => 'withdraw',
    ],
    'TRANSACTION_ACCOUNT_TYPE' => [
        'PRIVATE' => 'private',
        'BUSINESS' => 'business',
    ],
];
