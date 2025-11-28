<?php
namespace App\Models;

class Cart extends BaseModel {
    public static function addIfMissing(int $userId, int $packageId): void {
        $exists = self::pdo()->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ? AND package_id = ?");
        $exists->execute([$userId, $packageId]);
        if ((int)$exists->fetchColumn() === 0) {
            $ins = self::pdo()->prepare("INSERT INTO cart (user_id, package_id, declaration_value) VALUES (?,?,0)");
            $ins->execute([$userId, $packageId]);
        }
    }
    public static function updateDeclaration(int $cartId, float $value, int $userId): void {
        $upd = self::pdo()->prepare("UPDATE cart SET declaration_value = ? WHERE id = ? AND user_id = ?");
        $upd->execute([$value, $cartId, $userId]);
    }
    public static function listItems(int $userId): array {
        $stmt = self::pdo()->prepare("SELECT c.*, p.product_name, p.weight FROM cart c JOIN packages p ON p.id = c.package_id WHERE c.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public static function deleteById(int $id): void {
        self::pdo()->prepare("DELETE FROM cart WHERE id = ?")->execute([$id]);
    }
}
