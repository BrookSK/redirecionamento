<?php
namespace App\Core;

class Logger {
    public static function log(string $level, string $message, array $context = []): void {
        $dir = BASE_PATH . '/storage/logs';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $line = sprintf(
            "%s [%s] %s %s\n",
            date('c'),
            strtoupper($level),
            $message,
            $context ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : ''
        );
        @file_put_contents($dir . '/app.log', $line, FILE_APPEND);
    }
    public static function info(string $message, array $context = []): void { self::log('info', $message, $context); }
    public static function error(string $message, array $context = []): void { self::log('error', $message, $context); }
}
