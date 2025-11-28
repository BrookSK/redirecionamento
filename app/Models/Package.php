<?php
namespace App\Models;
use App\Core\Database;

class Package extends BaseModel {
    public static function find(int $id): ?array {
        $stmt = self::pdo()->prepare("SELECT * FROM packages WHERE id = ?");
        $stmt->execute([$id]);
        $r = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public static function search(array $filters, int $page = 1, int $perPage = 10): array {
        $where = [];
        $params = [];
        if (!empty($filters['suite_number'])) { $where[] = 'suite_number LIKE ?'; $params[] = '%'.$filters['suite_number'].'%'; }
        if (!empty($filters['status'])) { $where[] = 'status = ?'; $params[] = $filters['status']; }
        if (!empty($filters['received_from'])) { $where[] = 'received_date >= ?'; $params[] = $filters['received_from']; }
        if (!empty($filters['received_to'])) { $where[] = 'received_date <= ?'; $params[] = $filters['received_to']; }
        $sql = 'FROM packages';
        if ($where) { $sql .= ' WHERE ' . implode(' AND ', $where); }
        $total = (int) self::pdo()->prepare('SELECT COUNT(*) ' . $sql)->execute($params) ?: 0;
        $stmtCount = self::pdo()->prepare('SELECT COUNT(*) ' . $sql);
        $stmtCount->execute($params);
        $total = (int)$stmtCount->fetchColumn();
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = self::pdo()->prepare('SELECT * ' . $sql . ' ORDER BY id DESC LIMIT ? OFFSET ?');
        foreach ($params as $i=>$v) { $stmt->bindValue($i+1, $v); }
        $stmt->bindValue(count($params)+1, $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(count($params)+2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return ['rows'=>$rows,'total'=>$total];
    }

    public static function create(array $data): int {
        $stmt = self::pdo()->prepare("INSERT INTO packages (suite_number, product_name, supplier, ncm, received_date, weight, quantity, photo_url, status) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $data['suite_number'] ?? '',
            $data['product_name'] ?? '',
            $data['supplier'] ?? '',
            $data['ncm'] ?? '',
            $data['received_date'] ?? '',
            (float)($data['weight'] ?? 0),
            (int)($data['quantity'] ?? 1),
            $data['photo_url'] ?? '',
            $data['status'] ?? 'Pendente'
        ]);
        return (int) self::pdo()->lastInsertId();
    }

    public static function updateById(int $id, array $data): void {
        $stmt = self::pdo()->prepare("UPDATE packages SET suite_number=?, product_name=?, supplier=?, ncm=?, received_date=?, weight=?, quantity=?, photo_url=?, status=? WHERE id=?");
        $stmt->execute([
            $data['suite_number'] ?? '',
            $data['product_name'] ?? '',
            $data['supplier'] ?? '',
            $data['ncm'] ?? '',
            $data['received_date'] ?? '',
            (float)($data['weight'] ?? 0),
            (int)($data['quantity'] ?? 1),
            $data['photo_url'] ?? '',
            $data['status'] ?? 'Pendente',
            $id
        ]);
    }
}
