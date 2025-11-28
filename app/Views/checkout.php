<section>
  <h1>Checkout</h1>
  <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post" action="/checkout/process" class="grid-2">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <div class="card">
      <h2>Endereço de Envio</h2>
      <textarea name="shipping_address" rows="4" placeholder="Rua, número, cidade, país"></textarea>
      <label class="mt"><span>Moeda</span>
        <select name="currency">
          <option value="BRL" <?= $currency==='BRL'?'selected':'' ?>>BRL</option>
          <option value="USD" <?= $currency==='USD'?'selected':'' ?>>USD</option>
        </select>
      </label>
    </div>
    <div class="card">
      <h2>Resumo</h2>
      <ul class="summary">
        <li><span>Subtotal</span><strong><?= number_format($totals['subtotal'], 2) ?> <?= $currency ?></strong></li>
        <li><span>Taxas</span><strong><?= number_format($totals['taxes'], 2) ?> <?= $currency ?></strong></li>
        <li class="total"><span>Total</span><strong><?= number_format($totals['total'], 2) ?> <?= $currency ?></strong></li>
      </ul>
      <button type="submit" class="btn primary full">Pagar</button>
    </div>
  </form>
</section>
