<?php
namespace App\Models;

class User extends BaseModel {
    public static function find(int $id): ?array {
        $stmt = self::pdo()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $r = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $r ?: null;
    }
    public static function findByEmail(string $email): ?array {
        $stmt = self::pdo()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $r = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $r ?: null;
    }
    public static function create(array $data): int {
        $stmt = self::pdo()->prepare("INSERT INTO users(name,email,password,role,suite_number,preferred_currency) VALUES (?,?,?,?,?,?)");
        $stmt->execute([
            $data['name'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['role'] ?? 'client',
            $data['suite_number'] ?? '',
            $data['preferred_currency'] ?? 'BRL',
        ]);
        return (int) self::pdo()->lastInsertId();
    }
    public static function updatePreferredCurrency(int $id, string $currency): void {
        $stmt = self::pdo()->prepare("UPDATE users SET preferred_currency = ? WHERE id = ?");
        $stmt->execute([$currency, $id]);
    }
}
