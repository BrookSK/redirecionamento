<?php
namespace App\Core;
use PDO;

class Database {
    private static ?PDO $pdo = null;
    private static array $config;

    public static function init(array $config): void {
        self::$config = $config;
        $db = $config['db'] ?? [];
        $driver = $db['driver'] ?? 'sqlite';

        if ($driver === 'mysql') {
            $host    = $db['host']     ?? 'localhost';
            $dbname  = $db['database'] ?? '';
            $charset = $db['charset']  ?? 'utf8mb4';
            $user    = $db['username'] ?? null;
            $pass    = $db['password'] ?? null;

            $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
            self::$pdo = new PDO($dsn, $user, $pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } else {
            // Fallback para SQLite (modo atual de desenvolvimento)
            $dsn = 'sqlite:' . ($db['database'] ?? '');
            self::$pdo = new PDO($dsn);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->exec('PRAGMA foreign_keys = ON');
        }
    }

    public static function pdo(): PDO { return self::$pdo; }

    public static function driver(): string {
        return self::$config['db']['driver'] ?? 'sqlite';
    }

    public static function config(): array { return self::$config; }

    public static function migrateAndSeed(): void {
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            role TEXT NOT NULL,
            suite_number TEXT NOT NULL UNIQUE,
            preferred_currency TEXT NOT NULL DEFAULT 'BRL'
        );
        CREATE TABLE IF NOT EXISTS packages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            suite_number TEXT NOT NULL,
            product_name TEXT,
            supplier TEXT,
            ncm TEXT,
            received_date TEXT,
            weight REAL DEFAULT 0,
            quantity INTEGER DEFAULT 1,
            photo_url TEXT,
            status TEXT NOT NULL DEFAULT 'Pendente',
            order_id INTEGER NULL
        );
        CREATE TABLE IF NOT EXISTS cart (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            package_id INTEGER NOT NULL,
            declaration_value REAL DEFAULT 0
        );
        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            total_amount REAL NOT NULL,
            currency TEXT NOT NULL,
            payment_method TEXT NOT NULL,
            payment_status TEXT NOT NULL,
            shipping_address TEXT,
            created_at TEXT NOT NULL
        );
        CREATE TABLE IF NOT EXISTS order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER NOT NULL,
            package_id INTEGER NOT NULL,
            declaration_value REAL DEFAULT 0
        );
        CREATE TABLE IF NOT EXISTS tax_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            type TEXT NOT NULL,
            value REAL NOT NULL,
            currency TEXT NOT NULL
        );
        CREATE TABLE IF NOT EXISTS translations (
            key TEXT PRIMARY KEY,
            pt_br TEXT,
            en_us TEXT
        );
        ";
        self::$pdo->exec($sql);

        $cnt = (int) self::$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($cnt === 0) {
            $stmt = self::$pdo->prepare("INSERT INTO users(name,email,password,role,suite_number,preferred_currency) VALUES(?,?,?,?,?,?)");
            $stmt->execute([
                'Admin',
                'admin@example.com',
                password_hash('admin123', PASSWORD_DEFAULT),
                'admin',
                'PF-000000',
                'BRL'
            ]);
            $stmt->execute([
                'Cliente',
                'cliente@example.com',
                password_hash('cliente123', PASSWORD_DEFAULT),
                'client',
                'PF-000001',
                'BRL'
            ]);
        }

        $tcnt = (int) self::$pdo->query("SELECT COUNT(*) FROM tax_settings")->fetchColumn();
        if ($tcnt === 0) {
            $ins = self::$pdo->prepare("INSERT INTO tax_settings(name,type,value,currency) VALUES (?,?,?,?)");
            $ins->execute(['Serviço', 'flat', 10, 'BRL']);
            $ins->execute(['Manuseio', 'percent', 5, 'BRL']);
            $ins->execute(['Peso', 'weight', 2, 'BRL']);
            $ins->execute(['Service', 'flat', 5, 'USD']);
        }

        $tr = (int) self::$pdo->query("SELECT COUNT(*) FROM translations")->fetchColumn();
        if ($tr === 0) {
            $ins = self::$pdo->prepare("INSERT INTO translations(key, pt_br, en_us) VALUES (?,?,?)");
            $ins->execute(['login_title','Entrar','Sign In']);
            $ins->execute(['email','E-mail','Email']);
            $ins->execute(['password','Senha','Password']);
            $ins->execute(['sign_in','Entrar','Login']);
            $ins->execute(['logout','Sair','Logout']);
            $ins->execute(['admin_dashboard','Dashboard Admin','Admin Dashboard']);
            $ins->execute(['client_dashboard','Dashboard do Cliente','Client Dashboard']);
            $ins->execute(['packages','Pacotes','Packages']);
            $ins->execute(['orders','Pedidos','Orders']);
            $ins->execute(['cart','Carrinho','Cart']);
            $ins->execute(['checkout','Checkout','Checkout']);
        }
    }
}
