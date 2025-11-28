<?php
namespace App\Core;
use PDO;

class Translation {
    public static function t(string $key): string {
        $pdo = Database::pdo();
        $locale = $_SESSION['locale'] ?? 'pt_BR';
        $col = $locale === 'en_US' ? 'en_us' : 'pt_br';
        $stmt = $pdo->prepare("SELECT $col FROM translations WHERE key = ?");
        $stmt->execute([$key]);
        $v = $stmt->fetchColumn();
        return $v !== false && $v !== null && $v !== '' ? $v : $key;
    }
}
