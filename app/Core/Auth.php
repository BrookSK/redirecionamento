<?php
namespace App\Core;

class Auth {
    public static function user(): ?array {
        if (!isset($_SESSION['user_id'])) return null;
        $stmt = Database::pdo()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $u = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $u ?: null;
    }
    public static function check(): bool { return isset($_SESSION['user_id']); }
    public static function attempt(string $email, string $password): bool {
        $stmt = Database::pdo()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $u = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($u && password_verify($password, $u['password'])) {
            $_SESSION['user_id'] = (int)$u['id'];
            if (!isset($_SESSION['locale'])) $_SESSION['locale'] = 'pt_BR';
            return true;
        }
        
        return false;
    }
    public static function logout(): void {
        session_unset();
        session_destroy();
        session_start();
    }
    public static function requireLogin(): void {
        if (!self::check()) { header('Location: /login'); exit; }
    }
    public static function requireRole(string $role): void {
        self::requireLogin();
        $u = self::user();
        if (!$u || $u['role'] !== $role) {
            header('Location: /'); exit;
        }
    }
}
