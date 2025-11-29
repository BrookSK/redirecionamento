<?php
use App\Core\Auth;
use App\Core\Translation;
$locale = $_SESSION['locale'] ?? 'pt_BR';
$u = Auth::user();
?>
<!doctype html>
<html lang="<?= $locale === 'en_US' ? 'en' : 'pt-BR' ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($config['app_name'] ?? 'Package Forwarding') ?></title>
<link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
<header class="topbar">
  <div class="brand"><?= htmlspecialchars($config['app_name'] ?? 'App') ?></div>
  <nav class="nav">
    <?php if ($u && $u['role']==='admin'): ?>
      <a href="/admin/dashboard">Dashboard</a>
      <a href="/admin/packages">Pacotes</a>
      <a href="/admin/clients">Clientes</a>
      <a href="/logout"><?= Translation::t('logout') ?></a>
    <?php elseif ($u && $u['role']==='client'): ?>
      <a href="/client/dashboard">Dashboard</a>
      <a href="/cart"><?= Translation::t('cart') ?></a>
      <a href="/checkout"><?= Translation::t('checkout') ?></a>
      <a href="/client/orders"><?= Translation::t('orders') ?></a>
      <a href="/logout"><?= Translation::t('logout') ?></a>
    <?php else: ?>
      <a href="/login">Login</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
  <?php include $viewFile; ?>
</main>
<footer class="footer">
  <small>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name'] ?? 'App') ?></small>
</footer>
</body>
</html>
