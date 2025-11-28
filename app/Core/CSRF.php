<?php
namespace App\Core;

class CSRF {
    public static function token(): string {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }
    public static function validate(?string $token): bool {
        $ok = is_string($token) && isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
        if (!$ok) {
            http_response_code(419);
            echo 'CSRF token inválido';
            exit;
        }
        return true;
    }
}
