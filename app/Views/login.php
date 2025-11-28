<?php use App\Core\Translation; ?>
<section class="card">
  <h1><?= Translation::t('login_title') ?></h1>
  <?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" action="/login" class="form">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label>
      <span><?= Translation::t('email') ?></span>
      <input type="email" name="email" required>
    </label>
    <label>
      <span><?= Translation::t('password') ?></span>
      <input type="password" name="password" required>
    </label>
    <button type="submit" class="btn primary"><?= Translation::t('sign_in') ?></button>
  </form>
</section>
