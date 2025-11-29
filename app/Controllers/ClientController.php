<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;

class ClientController extends Controller {
    public function dashboard(): void {
        Auth::requireRole(['client','admin']);
        $u = Auth::user();
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM packages WHERE suite_number = ? AND (status = 'Pendente' OR status IS NULL) ORDER BY id DESC");
        $stmt->execute([$u['suite_number']]);
        $packages = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->view('client/dashboard', ['user'=>$u, 'packages'=>$packages]);
    }
    public function listOrders(): void {
        Auth::requireRole(['client','admin']);
        $u = Auth::user();
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$u['id']]);
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->view('client/orders', compact('orders','u'));
    }
    public function switchCurrency(array $params): void {
        Auth::requireRole(['client','admin']);
        $code = strtoupper($params['code'] ?? 'BRL');
        if (!in_array($code, ['BRL','USD'])) $code = 'BRL';
        $pdo = Database::pdo();
        $u = Auth::user();
        $stmt = $pdo->prepare("UPDATE users SET preferred_currency = ? WHERE id = ?");
        $stmt->execute([$code, $u['id']]);
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/client/dashboard');
    }
}
