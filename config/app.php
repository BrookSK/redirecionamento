<?php
$env = getenv('APP_ENV') ?: 'dev';

// Configurações de banco por ambiente (MySQL)
$dbConfigs = [
    'dev' => [
        'driver'   => 'mysql',
        'host'     => 'localhost',
        'database' => 'red_braz_dev',
        'username' => 'red_braz_dev',
        'password' => '_Mtyz&mw55dAM9it',
        'charset'  => 'utf8mb4',
    ],
    'test' => [
        'driver'   => 'mysql',
        'host'     => 'localhost',
        'database' => 'red_braz_dev',
        'username' => 'red_braz_dev',
        'password' => '_Mtyz&mw55dAM9it',
        'charset'  => 'utf8mb4',
    ],
];

$dbConfig = $dbConfigs[$env] ?? $dbConfigs['dev'];

return [
    'app_name' => 'Package Forwarding',
    'env' => $env,
    'locale' => 'pt_BR',
    'fallback_locale' => 'en_US',
    'supported_locales' => ['pt_BR', 'en_US'],
    'default_currency' => 'BRL',
    'supported_currencies' => ['BRL', 'USD'],
    'exchange_rates' => [
        'USD_BRL' => 5.00,
        'BRL_USD' => 0.20
    ],
    'db' => $dbConfig,
    'base_url' => '/',
];
