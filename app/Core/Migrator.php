<?php
namespace App\Core;

class Migrator {
    public static function run(): void {
        $pdo = Database::pdo();
        $dir = BASE_PATH . '/database/migrations';
        if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
        $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT UNIQUE, applied_at TEXT NOT NULL)");
        $applied = $pdo->query("SELECT name FROM migrations")->fetchAll(\PDO::FETCH_COLUMN) ?: [];
        $files = glob($dir . '/*.php') ?: [];
        sort($files);
        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, $applied, true)) continue;
            $callable = include $file;
            if (is_callable($callable)) {
                $pdo->beginTransaction();
                try {
                    $callable($pdo);
                    $ins = $pdo->prepare("INSERT INTO migrations(name, applied_at) VALUES (?, datetime('now'))");
                    $ins->execute([$name]);
                    $pdo->commit();
                } catch (\Throwable $e) {
                    $pdo->rollBack();
                    http_response_code(500);
                    echo 'Migration failed: ' . $name . ' - ' . $e->getMessage();
                    exit;
                }
            }
        }
    }
}
