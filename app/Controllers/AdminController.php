<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\CSRF;
use App\Core\Logger;
use App\Models\Package;

class AdminController extends Controller {
    public function dashboard(): void {
        Auth::requireRole('admin');
        $pdo = Database::pdo();
        $pending = (int)$pdo->query("SELECT COUNT(*) FROM packages WHERE status = 'Pendente'")->fetchColumn();
        $recentOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $newUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $this->view('admin/dashboard', compact('pending','recentOrders','newUsers'));
    }
    public function listClients(): void {
        Auth::requireRole('admin');
        $pdo = Database::pdo();
        $stmt = $pdo->query("SELECT id,name,email,role,suite_number FROM users ORDER BY id DESC");
        $clients = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->view('admin/clients', compact('clients'));
    }
    public function createClient(): void {
        Auth::requireRole('admin');
        $this->view('admin/create_client');
    }
    public function storeClient(): void {
        Auth::requireRole('admin');
        CSRF::validate($_POST['_csrf'] ?? null);
        $pdo = Database::pdo();
        $name = trim($_POST['name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $suite = trim($_POST['suite_number'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($name === '' || $email === '' || $suite === '' || $password === '') {
            $this->view('admin/create_client', ['error' => 'Preencha nome, e-mail, suite e senha']);
            return;
        }
        $exists = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $exists->execute([$email]);
        if ((int)$exists->fetchColumn() > 0) {
            $this->view('admin/create_client', ['error' => 'E-mail já cadastrado']);
            return;
        }
        $stmt = $pdo->prepare("INSERT INTO users(name,last_name,email,password,role,suite_number,preferred_currency,billing_street,billing_number,billing_neighborhood,billing_city,billing_state,billing_country,billing_complement,billing_zip,billing_phone,shipping_street,shipping_number,shipping_neighborhood,shipping_city,shipping_state,shipping_country,shipping_complement,shipping_zip,shipping_phone,cpf,birth_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $birth = $_POST['birth_date'] ?? null;
        if ($birth === '') $birth = null;
        $stmt->execute([
            $name,
            $lastName,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            'client',
            $suite,
            'BRL',
            $_POST['billing_street'] ?? null,
            $_POST['billing_number'] ?? null,
            $_POST['billing_neighborhood'] ?? null,
            $_POST['billing_city'] ?? null,
            $_POST['billing_state'] ?? null,
            $_POST['billing_country'] ?? null,
            $_POST['billing_complement'] ?? null,
            $_POST['billing_zip'] ?? null,
            $_POST['billing_phone'] ?? null,
            $_POST['shipping_street'] ?? null,
            $_POST['shipping_number'] ?? null,
            $_POST['shipping_neighborhood'] ?? null,
            $_POST['shipping_city'] ?? null,
            $_POST['shipping_state'] ?? null,
            $_POST['shipping_country'] ?? null,
            $_POST['shipping_complement'] ?? null,
            $_POST['shipping_zip'] ?? null,
            $_POST['shipping_phone'] ?? null,
            $_POST['cpf'] ?? null,
            $birth,
        ]);
        Logger::info('admin_client_created', ['email' => $email, 'suite' => $suite]);
        $this->redirect('/admin/clients');
    }
    public function showImportClients(): void {
        Auth::requireRole('admin');
        $this->view('admin/import_clients');
    }
    public function importClients(): void {
        Auth::requireRole('admin');
        CSRF::validate($_POST['_csrf'] ?? null);
        // Implementação detalhada da importação CSV será feita após definirmos formato e regra de senha
        $this->view('admin/import_clients', ['error' => 'Importação CSV ainda não implementada completamente.']);
    }
    public function listPackages(): void {
        Auth::requireRole('admin');
        $filters = [
            'suite_number' => $_GET['suite_number'] ?? '',
            'status' => $_GET['status'] ?? '',
            'received_from' => $_GET['received_from'] ?? '',
            'received_to' => $_GET['received_to'] ?? '',
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $res = Package::search($filters, $page, $perPage);
        $packages = $res['rows'];
        $total = $res['total'];
        $pages = (int)ceil(max(1, $total) / $perPage);
        $this->view('admin/packages', compact('packages','filters','page','pages','total'));
    }
    public function createPackage(): void {
        Auth::requireRole('admin');
        $this->view('admin/create_package');
    }
    public function storePackage(): void {
        Auth::requireRole('admin');
        CSRF::validate($_POST['_csrf'] ?? null);
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("INSERT INTO packages (suite_number, product_name, supplier, ncm, received_date, weight, quantity, photo_url, status) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $_POST['suite_number'] ?? '',
            $_POST['product_name'] ?? '',
            $_POST['supplier'] ?? '',
            $_POST['ncm'] ?? '',
            $_POST['received_date'] ?? '',
            (float)($_POST['weight'] ?? 0),
            (int)($_POST['quantity'] ?? 1),
            $_POST['photo_url'] ?? '',
            'Pendente'
        ]);
        Logger::info('package_created', ['suite' => $_POST['suite_number'] ?? '', 'product' => $_POST['product_name'] ?? '']);
        $this->redirect('/admin/packages');
    }

    public function editPackage(array $params): void {
        Auth::requireRole('admin');
        $id = (int)($params['id'] ?? 0);
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
        $stmt->execute([$id]);
        $pkg = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$pkg) { $this->redirect('/admin/packages'); }
        $this->view('admin/edit_package', ['p' => $pkg]);
    }

    public function updatePackage(array $params): void {
        Auth::requireRole('admin');
        $id = (int)($params['id'] ?? 0);
        CSRF::validate($_POST['_csrf'] ?? null);
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("UPDATE packages SET suite_number=?, product_name=?, supplier=?, ncm=?, received_date=?, weight=?, quantity=?, photo_url=?, status=? WHERE id=?");
        $stmt->execute([
            $_POST['suite_number'] ?? '',
            $_POST['product_name'] ?? '',
            $_POST['supplier'] ?? '',
            $_POST['ncm'] ?? '',
            $_POST['received_date'] ?? '',
            (float)($_POST['weight'] ?? 0),
            (int)($_POST['quantity'] ?? 1),
            $_POST['photo_url'] ?? '',
            $_POST['status'] ?? 'Pendente',
            $id
        ]);
        Logger::info('package_updated', ['id' => $id]);
        $this->redirect('/admin/packages');
    }

    public function taxSettings(): void {
        Auth::requireRole('admin');
        $pdo = Database::pdo();
        $rows = $pdo->query("SELECT * FROM tax_settings ORDER BY id DESC")->fetchAll(\PDO::FETCH_ASSOC);
        $this->view('admin/taxes', ['taxes' => $rows]);
    }

    public function createTax(): void {
        Auth::requireRole('admin');
        $this->view('admin/create_tax');
    }

    public function storeTax(): void {
        Auth::requireRole('admin');
        CSRF::validate($_POST['_csrf'] ?? null);
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("INSERT INTO tax_settings(name,type,value,currency) VALUES (?,?,?,?)");
        $stmt->execute([
            $_POST['name'] ?? '',
            $_POST['type'] ?? 'flat',
            (float)($_POST['value'] ?? 0),
            in_array($_POST['currency'] ?? 'BRL', ['BRL','USD']) ? $_POST['currency'] : 'BRL'
        ]);
        Logger::info('tax_created', ['name' => $_POST['name'] ?? '']);
        $this->redirect('/admin/settings/taxes');
    }

    public function deleteTax(array $params): void {
        Auth::requireRole('admin');
        $id = (int)($params['id'] ?? 0);
        CSRF::validate($_POST['_csrf'] ?? null);
        $pdo = Database::pdo();
        $pdo->prepare("DELETE FROM tax_settings WHERE id = ?")->execute([$id]);
        Logger::info('tax_deleted', ['id' => $id]);
        $this->redirect('/admin/settings/taxes');
    }

    public function editTax(array $params): void {
        Auth::requireRole('admin');
        $id = (int)($params['id'] ?? 0);
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM tax_settings WHERE id = ?");
        $stmt->execute([$id]);
        $tax = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$tax) { $this->redirect('/admin/settings/taxes'); }
        $this->view('admin/edit_tax', ['t' => $tax]);
    }

    public function updateTax(array $params): void {
        Auth::requireRole('admin');
        $id = (int)($params['id'] ?? 0);
        CSRF::validate($_POST['_csrf'] ?? null);
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("UPDATE tax_settings SET name=?, type=?, value=?, currency=? WHERE id = ?");
        $stmt->execute([
            $_POST['name'] ?? '',
            $_POST['type'] ?? 'flat',
            (float)($_POST['value'] ?? 0),
            in_array($_POST['currency'] ?? 'BRL', ['BRL','USD']) ? $_POST['currency'] : 'BRL',
            $id
        ]);
        Logger::info('tax_updated', ['id' => $id]);
        $this->redirect('/admin/settings/taxes');
    }
}
