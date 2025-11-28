<section>
  <h1>Carrinho</h1>
  <form method="post" action="/cart/update">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <div class="table-wrap">
    <table class="table">
      <thead><tr><th>Pacote</th><th>Peso</th><th>Valor Declaração</th></tr></thead>
      <tbody>
        <?php foreach ($items as $i): ?>
          <tr>
            <td><?= htmlspecialchars($i['product_name']) ?></td>
            <td><?= (float)$i['weight'] ?></td>
            <td>
              <input type="number" step="0.01" min="0" name="declaration[<?= (int)$i['id'] ?>]" value="<?= (float)$i['declaration_value'] ?>" required>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    <div class="right"><button class="btn primary" type="submit">Continuar para Checkout</button></div>
  </form>
</section>
