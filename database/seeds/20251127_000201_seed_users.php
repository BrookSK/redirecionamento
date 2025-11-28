<?php
return function (\PDO $pdo) {
    $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role,suite_number,preferred_currency) VALUES(?,?,?,?,?,?)");
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
};
