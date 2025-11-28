<section>
  <h1>Meus Pedidos</h1>
  <div class="table-wrap">
  <table class="table">
    <thead><tr><th>ID</th><th>Total</th><th>Moeda</th><th>Status</th><th>Criado em</th></tr></thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?= (int)$o['id'] ?></td>
          <td><?= number_format((float)$o['total_amount'], 2) ?></td>
          <td><?= htmlspecialchars($o['currency']) ?></td>
          <td><?= htmlspecialchars($o['payment_status']) ?></td>
          <td><?= htmlspecialchars($o['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</section>
