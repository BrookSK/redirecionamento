<?php
namespace App\Models;

class TaxSetting extends BaseModel {
    public static function all(): array {
        $st = self::pdo()->query("SELECT * FROM tax_settings ORDER BY id DESC");
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }
    public static function find(int $id): ?array {
        $st = self::pdo()->prepare("SELECT * FROM tax_settings WHERE id = ?");
        $st->execute([$id]);
        $r = $st->fetch(\PDO::FETCH_ASSOC);
        return $r ?: null;
    }
}
