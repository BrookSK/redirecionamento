<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

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
use App\Core\Router;
use App\Core\Migrator;
use App\Core\Seeder;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\ClientController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;

if (!is_dir(BASE_PATH.'/storage')) {
    mkdir(BASE_PATH.'/storage', 0777, true);
}

Database::init($config);
// Apenas em SQLite criamos arquivo e rodamos migrações/seed automáticos
if (Database::driver() === 'sqlite') {
    if (!file_exists($config['db']['database'])) {
        touch($config['db']['database']);
    }
    Migrator::run();
    Seeder::run();
}

$router = new Router($config);

$router->get('/', [AuthController::class, 'home']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/lang/{code}', [AuthController::class, 'switchLang']);

$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/admin/packages', [AdminController::class, 'listPackages']);
$router->get('/admin/packages/create', [AdminController::class, 'createPackage']);
$router->post('/admin/packages/create', [AdminController::class, 'storePackage']);
$router->get('/admin/packages/edit/{id}', [AdminController::class, 'editPackage']);
$router->post('/admin/packages/edit/{id}', [AdminController::class, 'updatePackage']);

$router->get('/admin/settings/taxes', [AdminController::class, 'taxSettings']);
$router->get('/admin/settings/taxes/create', [AdminController::class, 'createTax']);
$router->post('/admin/settings/taxes/create', [AdminController::class, 'storeTax']);
$router->post('/admin/settings/taxes/delete/{id}', [AdminController::class, 'deleteTax']);
$router->get('/admin/settings/taxes/edit/{id}', [AdminController::class, 'editTax']);
$router->post('/admin/settings/taxes/edit/{id}', [AdminController::class, 'updateTax']);

$router->get('/client/dashboard', [ClientController::class, 'dashboard']);
$router->get('/client/orders', [ClientController::class, 'listOrders']);
$router->get('/currency/{code}', [ClientController::class, 'switchCurrency']);

$router->get('/cart', [CartController::class, 'viewCart']);
$router->post('/cart/update', [CartController::class, 'update']);

$router->get('/checkout', [CheckoutController::class, 'viewCheckout']);
$router->post('/checkout/process', [CheckoutController::class, 'processPayment']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normaliza acessos que incluam "/public" no caminho, ex: /public/ ou /public/login
if (strpos($path, '/public') === 0) {
    $path = substr($path, strlen('/public'));
    if ($path === '' || $path === false) {
        $path = '/';
    }
}

// Remove barra final opcional (exceto da raiz), ex.: /login/ -> /login
if ($path !== '/' && substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
