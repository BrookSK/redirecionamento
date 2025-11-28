<?php
$env = getenv('APP_ENV') ?: 'dev';

// Permite override explícito do caminho via variável de ambiente DB_PATH
$defaultDbPaths = [
    'dev' => (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__)) . '/storage/database.sqlite',
    'prod' => (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__)) . '/storage/database.prod.sqlite',
    'test' => (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__)) . '/storage/database.test.sqlite',
];
$dbPath = getenv('DB_PATH') ?: ($defaultDbPaths[$env] ?? $defaultDbPaths['dev']);

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
    'db' => [
        'driver' => 'sqlite',
        'database' => $dbPath,
    ],
    'base_url' => '/',
];
