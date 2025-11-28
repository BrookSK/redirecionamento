<section>
  <h1>Dashboard do Cliente</h1>
  <p>Sua Suite: <strong><?= htmlspecialchars($user['suite_number']) ?></strong></p>
  <h2>Pacotes Pendentes</h2>
  <div class="table-wrap">
  <table class="table">
    <thead><tr><th>ID</th><th>Produto</th><th>Peso</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($packages as $p): ?>
        <tr>
          <td><?= (int)$p['id'] ?></td>
          <td><?= htmlspecialchars($p['product_name']) ?></td>
          <td><?= (float)$p['weight'] ?></td>
          <td><?= htmlspecialchars($p['status']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <p><a class="btn" href="/cart">Ir para o Carrinho</a></p>
</section>
