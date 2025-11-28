<?php
// Router for PHP built-in server: serve static from /public, else route to public/index.php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$publicDir = __DIR__ . '/public';

// Resolve filesystem path for static assets under /public
$fsPath = $publicDir . $path;
if (strpos($path, '/public/') === 0) {
    $fsPath = __DIR__ . $path;
}

if ($path !== '/' && is_file($fsPath)) {
    // Serve file content directly
    $mime = @mime_content_type($fsPath) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    readfile($fsPath);
    return true;
}

require $publicDir . '/index.php';
