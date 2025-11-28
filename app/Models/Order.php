<?php
namespace App\Models;

class Order extends BaseModel {
    public static function create(array $data): int {
        $stmt = self::pdo()->prepare("INSERT INTO orders(user_id,total_amount,currency,payment_method,payment_status,shipping_address,created_at) VALUES (?,?,?,?,?,?,datetime('now'))");
        $stmt->execute([
            (int)$data['user_id'],
            (float)$data['total_amount'],
            (string)$data['currency'],
            (string)$data['payment_method'],
            (string)$data['payment_status'],
            (string)($data['shipping_address'] ?? '')
        ]);
        return (int) self::pdo()->lastInsertId();
    }
    public static function listByUser(int $userId): array {
        $st = self::pdo()->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $st->execute([$userId]);
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }
}
