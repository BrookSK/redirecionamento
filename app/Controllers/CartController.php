<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\CSRF;
use App\Core\Logger;

class CartController extends Controller {
    private function sync(): void {
        $pdo = Database::pdo();
        $u = Auth::user();
        $stmt = $pdo->prepare("SELECT id FROM packages WHERE suite_number = ? AND status = 'Pendente'");
        $stmt->execute([$u['suite_number']]);
        $pkgIds = array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'id');
        foreach ($pkgIds as $pid) {
            $exists = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ? AND package_id = ?");
            $exists->execute([$u['id'], $pid]);
            if ((int)$exists->fetchColumn() === 0) {
                $ins = $pdo->prepare("INSERT INTO cart (user_id, package_id, declaration_value) VALUES (?,?,0)");
                $ins->execute([$u['id'], $pid]);
            }
        }
    }
    public function viewCart(): void {
        Auth::requireRole(['client','admin']);
        $this->sync();
        $u = Auth::user();
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT c.*, p.product_name, p.weight FROM cart c JOIN packages p ON p.id = c.package_id WHERE c.user_id = ?");
        $stmt->execute([$u['id']]);
        $items = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->view('cart', compact('items','u'));
    }
    public function update(): void {
        Auth::requireRole(['client','admin']);
        $u = Auth::user();
        $pdo = Database::pdo();
        CSRF::validate($_POST['_csrf'] ?? null);
        if (isset($_POST['declaration']) && is_array($_POST['declaration'])) {
            foreach ($_POST['declaration'] as $cid => $val) {
                $upd = $pdo->prepare("UPDATE cart SET declaration_value = ? WHERE id = ? AND user_id = ?");
                $upd->execute([(float)$val, (int)$cid, $u['id']]);
            }
            Logger::info('cart_updated', ['user_id' => $u['id']]);
        }
        $this->redirect('/checkout');
    }
}
