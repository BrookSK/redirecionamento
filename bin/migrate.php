<?php
// CLI: php bin/migrate.php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

$config = require BASE_PATH . '/config/app.php';

date_default_timezone_set('America/Sao_Paulo');

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_PATH . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative = str_replace('\\', '/', substr($class, $len));
    $file = $baseDir . $relative . '.php';
    if (file_exists($file)) require $file;
});

use App\Core\Database;
use App\Core\Migrator;
use App\Core\Seeder;

if (!is_dir(BASE_PATH.'/storage')) {
    mkdir(BASE_PATH.'/storage', 0777, true);
}
if (!file_exists($config['db']['database'])) {
    touch($config['db']['database']);
}

Database::init($config);
Migrator::run();
Seeder::run();

echo "Migrations e seeds executadas com sucesso.\n";
