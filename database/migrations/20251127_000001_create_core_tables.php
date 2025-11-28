<?php
return function (\PDO $pdo) {
    $sql = <<<'SQL'
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL,
        suite_number TEXT NOT NULL UNIQUE,
        preferred_currency TEXT NOT NULL DEFAULT 'BRL'
    );

    CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        total_amount REAL NOT NULL,
        currency TEXT NOT NULL,
        payment_method TEXT NOT NULL,
        payment_status TEXT NOT NULL,
        shipping_address TEXT,
        created_at TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
        order_id INTEGER NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
    );

    CREATE TABLE IF NOT EXISTS order_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER NOT NULL,
        package_id INTEGER NOT NULL,
        declaration_value REAL DEFAULT 0,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS cart (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        package_id INTEGER NOT NULL,
        declaration_value REAL DEFAULT 0,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
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

    CREATE INDEX IF NOT EXISTS ix_packages_suite ON packages(suite_number);
    CREATE INDEX IF NOT EXISTS ix_packages_status ON packages(status);
    CREATE INDEX IF NOT EXISTS ix_packages_received ON packages(received_date);
    CREATE UNIQUE INDEX IF NOT EXISTS ux_cart_user_pkg ON cart(user_id, package_id);
    CREATE INDEX IF NOT EXISTS ix_order_items_order ON order_items(order_id);
SQL;
    $pdo->exec($sql);
};
