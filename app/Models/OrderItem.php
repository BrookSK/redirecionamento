<?php
namespace App\Models;

class OrderItem extends BaseModel {
    public static function create(array $data): int {
        $st = self::pdo()->prepare("INSERT INTO order_items(order_id, package_id, declaration_value) VALUES (?,?,?)");
        $st->execute([(int)$data['order_id'], (int)$data['package_id'], (float)($data['declaration_value'] ?? 0)]);
        return (int) self::pdo()->lastInsertId();
    }
    public static function listByOrder(int $orderId): array {
        $st = self::pdo()->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $st->execute([$orderId]);
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }
}
