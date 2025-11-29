<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\CSRF;
use App\Core\Logger;

class AuthController extends Controller {
    public function home(): void {
        $this->view('home');
    }
    public function showLogin(): void {
        if (Auth::check()) { $this->redirect('/'); }
        $this->view('login');
    }
    public function login(): void {
        CSRF::validate($_POST['_csrf'] ?? null);
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if (Auth::attempt($email, $password)) {
            Logger::info('login_success', ['email' => $email]);
            $u = Auth::user();
            $this->redirect($u['role']==='admin' ? '/admin/dashboard' : '/client/dashboard');
        } else {
            Logger::info('login_fail', ['email' => $email]);
            $this->view('login', ['error' => 'Credenciais inválidas']);
        }
    }
    public function logout(): void {
        Logger::info('logout');
        Auth::logout();
        $this->redirect('/login');
    }
    public function switchLang(array $params): void {
        $code = $params['code'] ?? 'pt_BR';
        $_SESSION['locale'] = ($code === 'en_US') ? 'en_US' : 'pt_BR';
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function showRegister(): void {
        if (Auth::check()) { $this->redirect('/'); }
        $this->view('register');
    }

    public function register(): void {
        if (Auth::check()) { $this->redirect('/'); }
        CSRF::validate($_POST['_csrf'] ?? null);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $currency = $_POST['preferred_currency'] ?? 'BRL';
        if ($name === '' || $email === '' || $password === '') {
            $this->view('register', ['error' => 'Preencha todos os campos']);
            return;
        }
        $pdo = Database::pdo();
        $exists = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $exists->execute([$email]);
        if ((int)$exists->fetchColumn() > 0) {
            $this->view('register', ['error' => 'E-mail já cadastrado']);
            return;
        }
        $last = $pdo->query("SELECT suite_number FROM users WHERE suite_number LIKE 'PF-%' ORDER BY suite_number DESC LIMIT 1")->fetchColumn();
        $n = 0;
        if ($last) {
            $n = (int)substr((string)$last, 3);
        }
        $suite = sprintf('PF-%06d', $n + 1);
        $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role,suite_number,preferred_currency) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), 'client', $suite, in_array($currency,['BRL','USD'])?$currency:'BRL']);
        $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        Logger::info('user_registered', ['email' => $email, 'suite' => $suite]);
        $this->redirect('/client/dashboard');
    }
}
