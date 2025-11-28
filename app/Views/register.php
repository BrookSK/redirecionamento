<?php use App\Core\Translation; ?>
<section class="card">
  <h1>Registrar</h1>
  <?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" action="/register" class="form">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label>
      <span>Nome</span>
      <input type="text" name="name" required>
    </label>
    <label>
      <span><?= Translation::t('email') ?></span>
      <input type="email" name="email" required>
    </label>
    <label>
      <span><?= Translation::t('password') ?></span>
      <input type="password" name="password" required>
    </label>
    <label>
      <span>Moeda Preferida</span>
      <select name="preferred_currency">
        <option value="BRL">BRL</option>
        <option value="USD">USD</option>
      </select>
    </label>
    <button type="submit" class="btn primary">Criar Conta</button>
  </form>
  <p class="mt"><a href="/login">Já tem conta? Entrar</a></p>
</section>
