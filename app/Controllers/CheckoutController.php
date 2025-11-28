<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\CSRF;
use App\Core\Logger;

class CheckoutController extends Controller {
    private function totals(int $userId, string $currency): array {
        $pdo = Database::pdo();
        $items = $pdo->prepare("SELECT c.*, p.weight FROM cart c JOIN packages p ON p.id = c.package_id WHERE c.user_id = ?");
        $items->execute([$userId]);
        $items = $items->fetchAll(\PDO::FETCH_ASSOC);
        $subtotal = array_sum(array_map(fn($i)=>(float)$i['declaration_value'], $items));
        $weight = array_sum(array_map(fn($i)=>(float)$i['weight'], $items));
        $taxes = $pdo->prepare("SELECT * FROM tax_settings WHERE currency = ?");
        $taxes->execute([$currency]);
        $taxTotal = 0.0;
        foreach ($taxes->fetchAll(\PDO::FETCH_ASSOC) as $t) {
            if ($t['type']==='flat') $taxTotal += (float)$t['value'];
            elseif ($t['type']==='percent') $taxTotal += $subtotal * ((float)$t['value']/100);
            elseif ($t['type']==='weight') $taxTotal += $weight * (float)$t['value'];
        }
        return ['items'=>$items,'subtotal'=>$subtotal,'taxes'=>$taxTotal,'weight'=>$weight,'total'=>$subtotal+$taxTotal];
    }

    private function convert(float $amount, string $from, string $to): float {
        if ($from === $to) return $amount;
        $rates = $this->config['exchange_rates'];
        $key = $from.'_'.$to;
        if (isset($rates[$key])) return round($amount * (float)$rates[$key], 2);
        return $amount;
    }

    public function viewCheckout(): void {
        Auth::requireRole('client');
        $u = Auth::user();
        $currency = $u['preferred_currency'] ?? 'BRL';
        $t = $this->totals((int)$u['id'], $currency);
        $this->view('checkout', ['u'=>$u, 'currency'=>$currency, 'totals'=>$t]);
    }

    public function processPayment(): void {
        Auth::requireRole('client');
        $u = Auth::user();
        CSRF::validate($_POST['_csrf'] ?? null);
        $payCurrency = $_POST['currency'] ?? ($u['preferred_currency'] ?? 'BRL');
        $method = ($payCurrency === 'USD') ? 'stripe' : 'appmax';
        $pdo = Database::pdo();
        $t = $this->totals((int)$u['id'], $payCurrency);
        $amount = $t['total'];
        $success = $this->mockPay($amount, $payCurrency, $method);
        if ($success) {
            $stmt = $pdo->prepare("INSERT INTO orders(user_id,total_amount,currency,payment_method,payment_status,shipping_address,created_at) VALUES (?,?,?,?,?,?,datetime('now'))");
            $stmt->execute([$u['id'], $amount, $payCurrency, strtoupper($method), 'paid', $_POST['shipping_address'] ?? '']);
            $orderId = (int)$pdo->lastInsertId();
            $items = $t['items'];
            $ins = $pdo->prepare("INSERT INTO order_items(order_id, package_id, declaration_value) VALUES (?,?,?)");
            $upPkg = $pdo->prepare("UPDATE packages SET status = 'Pedido Criado', order_id = ? WHERE id = ?");
            $delCart = $pdo->prepare("DELETE FROM cart WHERE id = ?");
            foreach ($items as $i) {
                $ins->execute([$orderId, $i['package_id'], $i['declaration_value']]);
                $upPkg->execute([$orderId, $i['package_id']]);
                $delCart->execute([$i['id']]);
            }
            Logger::info('payment_success', ['user_id' => $u['id'], 'order_id' => $orderId, 'amount' => $amount, 'currency' => $payCurrency, 'method' => $method]);
            $this->redirect('/client/orders');
        } else {
            Logger::error('payment_failed', ['user_id' => $u['id'], 'amount' => $amount, 'currency' => $payCurrency, 'method' => $method]);
            $this->view('checkout', ['u'=>$u,'currency'=>$payCurrency,'totals'=>$t, 'error'=>'Pagamento falhou']);
        }
    }

    private function mockPay(float $amount, string $currency, string $method): bool {
        return $amount > 0;
    }
}
